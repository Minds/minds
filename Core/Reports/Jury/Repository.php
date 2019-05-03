<?php
namespace Minds\Core\Reports\Jury;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared\Custom as Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\Repository as ReportsManager;


class Repository
{
    /** @var Data\Cassandra\Client $cql */
    protected $cql;

    /** @var ReportsManager $reportsManager */
    private $reportsManager;

    public function __construct($cql = null, $reportsManager = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Client');
        $this->reportsManager = $reportsManager ?: new ReportsManager;
    }

    /**
     * Return the decisions a jury has made
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
        ], $opts);

        if (!$opts['user']->getPhoneNumberHash()) {
            return null;
        }

        $statement = "SELECT * FROM moderation_reports_by_state
            WHERE state = ?";

        $values = [
            $opts['juryType'] === 'appeal' ? 'appealed' : 'reported',
        ];

        $prepared = new Prepared;
        $prepared->query($statement, $values);

        $result = $this->cql->request($prepared);

        $response = new Response;

        foreach ($result as $row) {
            if (in_array($opts['user']->getPhoneNumberHash(), array_map($row['user_hashes']->values(), function ($hash) {
                return $hash->value();
            }))) {
                continue; // Already interacted with
            }

            $report = new Report();
            $report->setEntityUrn($row['entity_urn'])
                ->setReasonCode($row['reason_code']->value())
                ->setSubReasonCode($row['sub_reason-code']->value());
                
            $response[] = $this->reportsManager->buildFromRow($row);
        }

        foreach ($reports as $report) {
            $response[] = $report;
        }

        return $response;
    }

    /**
     * Add a decision for a report
     * @param Decision $decision
     * @return boolean
     */
    public function add(Decision $decision)
    {
        $juryType = $decision->isAppeal() ? 'appeal_jury' : 'initial_jury';
        $body = [
            'script' => [
                'inline' => "if (ctx._source.$juryType === null) { 
                        ctx._source.$juryType = [];
                    } 
                    ctx._source.$juryType.add(params.decision)",
                'lang' => 'painless',
                'params' => [
                    'decision' => [
                        [
                            '@timestamp' => (int) $decision->getTimestamp(), // In MS
                            'juror_guid' => (int) $decision->getJurorGuid(),
                            'juror_hash' => (string) $decision->getJurorHash(),
                            //'accepted' => true,
                            'action' => $decision->getAction(),
                        ],
                    ],
                ],
            ],
            'scripted_upsert' => true,
            'upsert' => [
                'entity_guid' => $decision->getReport()->getEntityGuid(),
                $juryType => [],
            ],
        ];

        $query = [
            'index' => 'minds-moderation',
            'type' => 'reports',
            'id' => $decision->getReport()->getEntityGuid(),
            'body' => $body,
        ];

        $prepared = new Prepared\Update();
        $prepared->query($query);

        return (bool) $this->es->request($prepared);
    }

}
