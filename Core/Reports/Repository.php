<?php
namespace Minds\Core\Reports;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Reports\ReportedEntity;

class Repository
{
    /** @var Data\ElasticSearch\Client $es */
    protected $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * Return the reports available to a jury
     * @param array $options 'limit', 'offset', 'state'
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'state' => '',
            'owner' => null,
            'juryType' => 'appeal',
            'user' => null,
            'must' => [],
            'must_not' => [],
        ], $opts);

        $must = $opts['must'];
        $must_not = $opts['must_not'];

        $body = [
            'query' => [
                'bool' => [
                    'must' => $must,
                    'must_not' => $must_not,
                ]
            ],
            'size' => $opts['limit'],
        ];

        $response = new Response;

        $prepared = new Prepared\Search();
        $prepared->query([
            'index' => 'minds-moderation',
            'type' => 'reports',
            'body' => $body,
            'size' => $opts['limit'],
        ]);

        $result = $this->es->request($prepared);

        foreach ($result['hits']['hits'] as $row) {
            $report = new Report();
            $report
                ->setEntityGuid($row['_source']['entity_guid'])
                ->setReports($this->buildReports($row['_source']))
                ->setAppeal(isset($row['_source']['@initial_jury_decided_timestamp']))
                ->setAppealNote($row['_source']['appeal_note'] ?? '')
                ->setAppealTimestamp($row['_source']['@appeal_timestamp'] ?? null)
                ->setInitialJuryDecisions($this->buildDecisions($row, 'initial'))
                ->setInitialJuryDecidedTimestamp($row['_source']['@initial_jury_decided_timestamp'] ?? null)
                ->setAppealJuryDecisions($this->buildDecisions($row, 'appeal'))
                ->setInitialJuryDecidedTimestamp($row['_source']['@appeal_jury_decided_timestamp'] ?? null);

            $response[] = $report;
        }

        return $response;
    }

    /**
     * Return a single report
     * @param int $entity_guid
     * @return ReportEntity
     */
    public function get($entity_guid)
    {
        $prepared = new Prepared\Document();
        $prepared->query([
            'index' => 'minds-moderation',
            'type' => 'reports',
            'id' => $entity_guid,
        ]);

        $result = $this->es->request($prepared);

        $report = new Report();
        $report
            ->setEntityGuid($result['_source']['entity_guid'])
            ->setReports($this->buildReports($result['_source']))
            ->setAppeal(isset($result['_source']['@initial_jury_decided_timestamp']))
            ->setInitialJuryDecisions($this->buildDecisions($result, 'initial'))
            ->setAppealJuryDecisions($this->buildDecisions($result, 'appeal'));

        return $report;
    }

    /**
     * void
     */
    public function add(Report $report)
    {
        
    }

    private function buildReports($set)
    {
        $return = [];

        foreach ($set as $userGuid) {
            $return[] = $report = new UserReport();
            $report
                //->setTimestamp($row[0]['@timestamp'])
                //->setEntityUrn($source['entity_guid'])
                ->setReporterGuid($userGuid->value());
        }
        return $return;
    }

    private function buildDecisions($row, $juryType = 'initial')
    {
        $jurorGuids = [];

        $return = [];

        $reports = $juryType === 'appeal'
            ? ($row['_source']['appeal_jury'] ?? [])
            : ($row['_source']['initial_jury'] ?? []);

        foreach ($reports as $row) {
            if (!isset($row[0]['action'])) {
                continue; // Something didn't save properly
            }
            if (isset($jurorGuids[$row[0]['juror_guid']])) { //TODO: change to juror_hash
                continue; // avoid duplicate reports
            }
            $jurorGuids[$row[0]['juror_guid']] = true; 

            $decision = new Jury\Decision();
            $decision
                ->setTimestamp($row[0]['@timestamp'])
                ->setEntityGuid($row['_source']['entity_guid'])
                ->setJurorGuid($row[0]['juror_guid'])
                ->setAction($row[0]['action']);
            $return[] = $decision;
        }
        return $return;
    }

    /**
     * Build from a row 
     * @param array $row
     * @return Report
     */
    public function buildFromRow($row)
    {
        $report = new Report;
        $report->setEntityUrn((string) $row['entity_urn'])
            ->setEntityOwnerGuid($row['entity_owner_guid']->value())
            ->setReasonCode($row['reason_code']->toFloat())
            ->setSubReasonCode($row['sub_reason_code']->toFloat())
            ->setTimestamp($row['timestamp']->time())
            ->setState((string) $row['state'])
            ->setUphold(isset($row['uphold']) ? (bool) $row['uphold'] : null)
            ->setStateChanges($row['state_changes']->values())
            ->setAppealNote(isset($row['appeal_note']) ? (string) $row['appeal_note'] : '')
            ->setReports(
                $this->buildReports($row['reports']->values())
            )
            ->setInitialJuryDecisions(
                isset($row['initial_jury']) ?
                    $this->buildDecisions($row['initial_jury']->values())
                    : null
            )
            ->setAppealJuryDecision(
                isset($row['appeal_jury']) ?
                    $this->buildDecisions($row['appeal_jury']->values())
                    : null
            )
            ->setUserHashes($row['user_hashes']->values());
        return $report;
    }

}
