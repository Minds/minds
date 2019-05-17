<?php
namespace Minds\Core\Reports;

use Cassandra;
use Cassandra\Decimal;
use Cassandra\Timestamp;
use Cassandra\Tinyint;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Reports\ReportedEntity;
use Minds\Common\Urn;

class Repository
{
    /** @var Data\Cassandra\Client $cql */
    protected $cql;

    /** @var Urn $urn */
    protected $urn;

    public function __construct($cql = null, $urn = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->urn = $urn ?: new Urn;
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
            'timestamp' => null
        ], $opts);

        /*$must = $opts['must'];
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
        }*/

        $response = new Response;

        $statement = "SELECT * FROM moderation_reports";

        $where = [];
        $values = [];

        if ($opts['entity_urn']) {
            $where[] = "entity_urn = ?";
            $values[] = $opts['entity_urn'];
        }

        if (isset($opts['reason_code'])) {
            $where[] = "reason_code = ?";
            $values[] = new Tinyint($opts['reason_code']);
        }

        if (isset($opts['sub_reason_code'])) {
            $where[] = "sub_reason_code = ?";
            $values[] = new Decimal($opts['sub_reason_code']);
        }

        if ($opts['timestamp']) {
            $where[] = "timestamp = ?";
            $values[] = new Timestamp($opts['timestamp']);
        }

        if ($where) {
            $statement .= " WHERE " . implode(' AND ', $where);
        }

        $prepared = new Prepared\Custom();
        $prepared->query($statement, $values);

        $rows = $this->cql->request($prepared);

        foreach ($rows as $row) {
            $response[] = $this->buildFromRow($row);
        }

        return $response;
    }

    /**
     * Return a single report
     * @param string $urn
     * @return Report
     * @throws \Exception
     */
    public function get($urn)
    {
        $parts = explode('-', $this->urn->setUrn($urn)->getNss());

        $entityUrn = substr($parts[0], 1, -1); // Remove the parenthases
        $reasonCode = $parts[1];
        $subReasonCode = $parts[2] ?? 0;
        $timestamp = $parts[3];
        

        $response = $this->getList([
            'entity_urn' => $entityUrn,
            'reason_code' => $reasonCode,
            'sub_reason_code' => $subReasonCode,
            'timestamp' => $timestamp,
        ]);
        
        if (!$response[0]) {
            return null;
        }

        return $response[0];
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

        foreach ($row as $jurorGuid => $uphold) {
            if (isset($jurorGuids[$jurorGuid])) { //TODO: change to juror_hash
                continue; // avoid duplicate reports
            }

            $jurorGuids[$jurorGuid] = true; 

            $decision = new Jury\Decision();
            $decision
                ->setJurorGuid($jurorGuid)
                ->setUphold($uphold);
            $return[] = $decision;
        }
        return $return;
    }

    private function mapToAssoc($map)
    {
        $assoc = [];
        foreach ($map as $k => $v) {
            $assoc[(string) $k] = $v;
        }
        return $assoc;
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
            ->setEntityOwnerGuid(isset($row['entity_owner_guid']) ? $row['entity_owner_guid']->value() : null)
            ->setReasonCode($row['reason_code']->value())
            ->setSubReasonCode($row['sub_reason_code']->value())
            ->setTimestamp($row['timestamp']->time())
            //->setState((string) $row['state'])
            ->setUphold(isset($row['uphold']) ? (bool) $row['uphold'] : null)
            ->setStateChanges(isset($row['state_changes']) ? 
                array_map(function ($timestamp) {
                    return $timestamp->time();
                }, $this->mapToAssoc($row['state_changes']))
                : null
            )
            ->setAppeal(isset($row['appeal_note']) ? true : false)
            ->setAppealNote(isset($row['appeal_note']) ? (string) $row['appeal_note'] : '')
            ->setReports(
                $this->buildReports($row['reports']->values())
            )
            ->setInitialJuryDecisions(
                isset($row['initial_jury']) ?
                    $this->buildDecisions($this->mapToAssoc($row['initial_jury']))
                    : null
            )
            ->setAppealJuryDecisions(
                isset($row['appeal_jury']) ?
                    $this->buildDecisions($this->mapToAssoc($row['appeal_jury']))
                    : null
            )
            ->setUserHashes(isset($row['user_hashes']) ? 
                $row['user_hashes']->values() : null
            );
        return $report;
    }

}
