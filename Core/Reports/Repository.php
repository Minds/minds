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

    private function buildReports($source)
    {
        $reporterGuids = [];

        $return = [];

        $reports = $source['reports'];

        foreach ($reports as $row) {
            if (isset($reportGuids[$row[0]['reporter_guid']])) {
                continue; // avoid duplicate reports
            }
            $reporterGuids[$row[0]['reporter_guid']] = true; 

            $return[] = $report = new UserReport();
            $report
                ->setTimestamp($row[0]['@timestamp'])
                ->setEntityGuid($source['entity_guid'])
                ->setReporterGuid($row[0]['reporter_guid'])
                ->setReasonCode($row[0]['reason'])
                ->setSubReasonCode($row[0]['sub_reason'] ?? null);
            
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

}
