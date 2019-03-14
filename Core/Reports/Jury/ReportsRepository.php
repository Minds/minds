<?php
namespace Minds\Core\Reports\Jury;

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
use Minds\Core\Reports\ReportedEntity;

class ReportsRepository
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
        ], $opts);

        $must = [];
        $must_not = [];
        
        if ($opts['juryType'] == 'appeal') {
            $must[] = [
                'exists' => [
                    'field' => '@initial_jury_decided_timestamp',
                ],
            ];
            $must_not[] = [
                'exists' => [
                    'field' => '@appeal_jury_decided_timestamp',
                ],
            ];
        } else {
            $must_not[] = [
                'exists' => [
                    'field' => '@initial_jury_decided_timestamp',
                ],
            ];
            $must_not[] = [
                'exists' => [
                    'field' => '@appeal_jury_decided_timestamp',
                ],
            ];
        }

        // Do not show what we have reported or previously juried on
        if ($opts['user']) {
            $must_not[] = [
                'nested' => [
                    'path' => 'reports',
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'match' => [
                                        'reports.reporter_guid' => $opts['user']->getGuid(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ];

            $must_not[] = [
                'nested' => [
                    'path' => 'initial_jury',
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'match' => [
                                        'initial_jury.juror_guid' => $opts['user']->getGuid(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ];

            $must_not[] = [
                'nested' => [
                    'path' => 'appeal_jury',
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'match' => [
                                        'appeal_jury.juror_guid' => $opts['user']->getGuid(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ];
        }

        $body = [
            'query' => [
                'bool' => [
                    'must' => $must,
                    'must_not' => $must_not,
                ]
            ],
            'size' => $opts['limit'],
            'aggs' => [ // We use aggregates as we can't rely on nested not having duplicate reports
                'reports' => [
                    'terms' => [
                        'field' => 'entity_guid'
                    ],
                    'aggs' => [
                        'reports' => [
                            'nested' => [
                                'path' => 'reports',
                            ],
                            'aggs' => [
                                'report' => [
                                    'terms' => [
                                        'field' => 'report.reporter_guid',
                                    ],
                                    'aggs' => [
                                        'reason' => [
                                            'terms' => [
                                                'field' => 'report.reason',
                                            ],
                                        ],
                                        'reason' => [
                                            'terms' => [
                                                'field' => 'report.sub_reason',
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
        $prepared->query([
            'index' => 'minds-moderation',
            'type' => 'reports',
            'body' => $body,
            'size' => $opts['limit'],
        ]);

        $result = $this->es->request($prepared);

        foreach ($result['hits']['hits'] as $row) {


            $report = new ReportedEntity();
            $report
                ->setEntityGuid($row['_source']['entity_guid'])
                ->setReports($this->buildReports($row['_source']));

            /*$decisions = [];
            foreach ($result['aggregations']['decisions']['buckets'][$reportStage]['decision']['buckets'] as $decision_row) {
                $decisions[] = (new Decision)
                    ->setJurorGuid($decision_row['key'])
                    //->setTimestamp($decision_row['@timestamp'])
                    ->setAction($decision_row['action']['buckets'][0]['key']);
            }
            $verdict->setDecisions($decisions);*/

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

        $report = new ReportedEntity();
        $report
            ->setEntityGuid($result['_source']['entity_guid'])
            ->setReports($this->buildReports($result['_source']));

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

            $return[] = $report = new Report();
            $report
                ->setTimestamp($row[0]['@timestamp'])
                ->setEntityGuid($source['entity_guid'])
                ->setReporterGuid($row[0]['reporter_guid'])
                ->setReasonCode($row[0]['reason'])
                ->setSubReasonCode($row[0]['sub_reason'] ?? null);
            
        }
        return $return;
    }

}
