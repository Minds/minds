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
use Minds\Core\Reports\Repository as ReportsRepository;


class ElasticRepository
{
    /** @var Data\ElasticSearch\Client $es */
    protected $es;

    /** @var ReportsRepository $reportsRepository */
    private $reportsRepository;

    public function __construct($es = null, $reportsRepository = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
        $this->reportsRepository = $reportsRepository ?: new ReportsRepository;
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

        $must = [];
        $must_not = [];

        // Must never show own posts
        $must_not[] = [
            'match' => [
                'entity_owner_guid' => (int) $opts['user']->getGuid(),
            ],
        ];
        
        if ($opts['juryType'] == 'appeal') {
            $must[] = [
                'exists' => [
                    'field' => '@initial_jury_decided_timestamp',
                ],
            ];
            $must[] = [
                'exists' => [
                    'field' => '@appeal_timestamp',
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
                                        'initial_jury.juror_hash' => $opts['user']->getPhoneNumberHash(),
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
                                        'appeal_jury.juror_hash' => $opts['user']->getPhoneNumberHash(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ];
        }

        $opts['must'] = $must;
        $opts['must_not'] = $must_not;

        $response = new Response;

        $reports = $this->reportsRepository->getList($opts);

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
