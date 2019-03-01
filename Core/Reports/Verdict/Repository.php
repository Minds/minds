<?php
namespace Minds\Core\Reports\Verdict;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;
use Minds\Common\Repository\Response;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Jury\Decision;


class Repository
{
    /** @var Data\ElasticSearch\Client $es */
    protected $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * Return the decisions a jury has made
     * @param array $options 'limit', 'offset', 'state'
     * @return array
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'state' => '',
            'owner' => null
        ], $opts);

        $must = [];

        if ($opts['entity_guid']) {
            $must[] = [
                'match' => [
                    'entity_guid' => $opts['entity_guid'],
                ],
            ];
        }

        $body = [
            'query' => [
                'bool' => [
                    'must' => $must,   
                ]
            ],
            'size' => $opts['limit'],
            'aggs' => [ // We use aggregates as we can't rely on nested not having duplicate votes
                'decisions' => [
                    'terms' => [
                        'field' => 'entity_guid'
                    ],
                    'aggs' => [
                        'initial_jury' => [
                            'nested' => [
                                'path' => 'initial_jury',
                            ],
                            'aggs' => [
                                'decision' => [
                                    'terms' => [
                                        'field' => 'initial_jury.reporter_guid',
                                    ],
                                    'aggs' => [
                                        'action' => [
                                            'terms' => [
                                                'field' => 'initial_jury.action',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'appeal_jury' => [
                            'nested' => [
                                'path' => 'appeal_jury',
                            ],
                            'aggs' => [
                                'decision' => [
                                    'terms' => [
                                        'field' => 'appeal_jury.reporter_guid',
                                    ],
                                    'aggs' => [
                                        'action' => [
                                            'terms' => [
                                                'field' => 'appeal_jury.action',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];


        $response = new Response;

        $prepared = new Prepared\Search();
        $result = $this->es->request($prepared);

        foreach ($result['hits']['hits'] as $row) {

            $report = new Report();
            $report->setEntityGuid($row['_source']['entity_guid']);

            $verdict = new Verdict();
            $verdict->setAppeal(isset($row['_source']['@initial_jury_decided_timestamp']))
                ->setReport($report);

            $reportStage = $verdict->isAppeal() ? 'appeal_jury' : 'initial_jury';
            $decisions = [];
            foreach ($result['aggregations']['decisions']['buckets'][$reportStage]['decision']['buckets'] as $decision_row) {
                $decisions[] = (new Decision)
                    ->setJurorGuid($decision_row['key'])
                    //->setTimestamp($decision_row['@timestamp'])
                    ->setAction($decision_row['action']['buckets'][0]['key']);
            }
            $verdict->setDecisions($decisions);

            $response[] = $verdict;
        }

        return $response;
    }

    /**
     * @param $entity_guid
     * @return Verdict
     */
    public function get($entity_guid)
    {
        $response = $this->getList([
            'entity_guid' => $entity_guid,
        ]);

        if ($response[0] 
            && $response[0]->getReport()
            && $response[0]->getReport()->getEntityGuid() == $entity_guid
        ) {
            return $response[0];
        }

        return null;
    }

    /**
     * Add a decision for a report
     * @param Decision $decision
     * @return boolean
     */
    public function add(Verdict $verdict)
    {
        $reportStage = $verdict->isAppeal() ? 'appeal_jury_' : 'initial_jury_';
        $body = [
            'doc' => [
                "@{$reportStage}decided_timestamp" => $verdict->getTimestamp(),
                $reportStage . 'action' => $verdict->getAction(),
            ],
            'doc_as_upsert' => true,
        ];

        $query = [
            'index' => 'minds-moderation',
            'type' => 'reports',
            'id' => $verdict->getReport()->getEntityGuid(),
            'body' => $body,
        ];

        $prepared = new Prepared\Update();
        $prepared->query($query);

        return (bool) $this->es->request($prepared);
    }

}
