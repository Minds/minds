<?php

namespace Minds\Core\Feeds\Top;

use Minds\Core\Data\ElasticSearch\Client as ElasticsearchClient;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Di\Di;
use Minds\Core\Search\SortingAlgorithms;
use Minds\Helpers\Text;

class Repository
{
    /** @var ElasticsearchClient */
    protected $client;

    protected $index;

    /** @var array $pendingBulkInserts * */
    private $pendingBulkInserts = [];

    public function __construct($client = null, $config = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');

        $config = $config ?: Di::_()->get('Config');

        $this->index = $config->get('elasticsearch')['index'];
    }

    /**
     * @param array $opts
     * @return \Generator|ScoredGuid[]
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'offset' => 0,
            'limit' => 12,
            'container_guid' => null,
            'owner_guid' => null,
            'subscriptions' => null,
            'access_id' => null,
            'custom_type' => null,
            'hashtags' => [],
            'filter_hashtags' => true,
            'type' => null,
            'period' => null,
            'algorithm' => null,
            'query' => null,
            'nsfw' => null,
            'from_timestamp' => null,
            'exclude_moderated' => false,
            'moderation_reservations' => null
        ], $opts);

        if (!$opts['type']) {
            throw new \Exception('Type must be provided');
        }

        if (!$opts['algorithm']) {
            throw new \Exception('Algorithm must be provided');
        }

        if (!in_array($opts['period'], ['12h', '24h', '7d', '30d', '1y'])) {
            throw new \Exception('Unsupported period');
        }

        $body = [
            '_source' => array_unique([
                'guid',
                'owner_guid',
                '@timestamp',
                'time_created',
                'access_id',
                'moderator_guid',
                $this->getSourceField($opts['type']),
            ]),
            'query' => [
                'function_score' => [
                    'query' => [
                        'bool' => [
                            //'must_not' => [ ],
                        ],
                    ],
                    "score_mode" => "sum",
                    'functions' => [
                        [
                            'filter' => [
                                'match_all' => (object) [],
                            ],
                            'weight' => 1,
                        ],
                    ],
                ],
            ],
            'sort' => [],
        ];

        /*if ($opts['type'] === 'group' && false) {
            if (!isset($body['query']['function_score']['query']['bool']['must_not'])) {
                $body['query']['function_score']['query']['bool']['must_not'] = [];
            }
            $body['query']['function_score']['query']['bool']['must_not'][] = [
                'terms' => [
                    'access_id' => ['0', '1', '2'],
                ],
            ];
        } elseif ($opts['type'] === 'user') {
            $body['query']['function_score']['query']['bool']['must'][] = [
                'term' => [
                    'access_id' => '2',
                ],
            ];
        }*/

        //

        switch ($opts['algorithm']) {
            case "top":
                $algorithm = new SortingAlgorithms\Top();
                break;
            case "controversial":
                $algorithm = new SortingAlgorithms\Controversial();
                break;
            case "hot":
                $algorithm = new SortingAlgorithms\Hot();
                break;
            case "latest":
            default:
                $algorithm = new SortingAlgorithms\Chronological();
                break;
        }

        $algorithm->setPeriod($opts['period']);

        //

        if ($opts['container_guid']) {
            $containerGuids = Text::buildArray($opts['container_guid']);

            if (!isset($body['query']['function_score']['query']['bool']['must'])) {
                $body['query']['function_score']['query']['bool']['must'] = [];
            }

            $body['query']['function_score']['query']['bool']['must'][] = [
                'terms' => [
                    'container_guid' => $containerGuids,
                ],
            ];
        }

        if ($opts['owner_guid']) {
            $ownerGuids = Text::buildArray($opts['owner_guid']);

            if (!isset($body['query']['function_score']['query']['bool']['must'])) {
                $body['query']['function_score']['query']['bool']['must'] = [];
            }

            $body['query']['function_score']['query']['bool']['must'][] = [
                'terms' => [
                    'owner_guid' => $ownerGuids,
                ],
            ];
        } elseif ($opts['subscriptions']) {
            if (!isset($body['query']['function_score']['query']['bool']['must'])) {
                $body['query']['function_score']['query']['bool']['must'] = [];
            }

            $body['query']['function_score']['query']['bool']['must'][] = [
                'bool' => [
                    'should' => [
                        [
                            'terms' => [
                                'owner_guid' => [
                                    'index' => 'minds-graph',
                                    'type' => 'subscriptions',
                                    'id' => (string) $opts['subscriptions'],
                                    'path' => 'guids',
                                ],
                            ],
                        ],
                        [
                            'term' => [
                                'owner_guid' => (string) $opts['subscriptions'],
                            ],
                        ],
                    ],
                ],
            ];
        }

        if ($opts['custom_type']) {
            $customTypes = Text::buildArray($opts['custom_type']);

            if (!isset($body['query']['function_score']['query']['bool']['must'])) {
                $body['query']['function_score']['query']['bool']['must'] = [];
            }

            $body['query']['function_score']['query']['bool']['must'][] = [
                'terms' => [
                    'custom_type' => $customTypes,
                ],
            ];
        }

        if ($opts['nsfw'] !== null) {
            $nsfw = array_diff([1, 2, 3, 4, 5, 6], $opts['nsfw']);
            if ($nsfw) {
                $body['query']['function_score']['query']['bool']['must_not'][] = [
                    'terms' => [
                        'nsfw' => array_values($nsfw),
                    ],
                ];

                if (in_array(6, $nsfw)) { // 6 is legacy 'mature'
                    $body['query']['function_score']['query']['bool']['must_not'][] = [
                        'term' => [
                            'mature' => true,
                        ],
                    ];
                }
            }
        }

        if ($opts['type'] !== 'group' && $opts['access_id'] !== null) {
            $body['query']['function_score']['query']['bool']['must'][] = [
                'terms' => [
                    'access_id' => Text::buildArray($opts['access_id']),
                ],
            ];
        }

        if ($opts['from_timestamp']) {
            $body['query']['function_score']['query']['bool']['must'][] = [
                'range' => [
                    '@timestamp' => [
                        'lte' => (int) $opts['from_timestamp'],
                    ],
                ],
            ];
        }

        //
        if ($opts['query']) {
            $words = explode(' ', $opts['query']);

            if (count($words) === 1) {
                $body['query']['function_score']['query']['bool']['must'][] = [
                    'multi_match' => [
                        'query' => $opts['query'],
                        'fields' => ['name^2', 'title^12', 'message^12', 'description^12', 'brief_description^8', 'username^8', 'tags^64'],
                    ],
                ];
            } else {
                $body['query']['function_score']['query']['bool']['must'][] = [
                    'multi_match' => [
                        'query' => $opts['query'],
                        'type' => 'phrase',
                        'fields' => ['name^2', 'title^12', 'message^12', 'description^12', 'brief_description^8', 'username^8', 'tags^16'],
                    ],
                ];
            }
        } elseif ($opts['hashtags']) {
            if ($opts['filter_hashtags'] || $algorithm instanceof SortingAlgorithms\Chronological) {
                if (!isset($body['query']['function_score']['query']['bool']['must'])) {
                    $body['query']['function_score']['query']['bool']['must'] = [];
                }

                $body['query']['function_score']['query']['bool']['must'][] = [
                    'terms' => [
                        'tags' => $opts['hashtags'],
                    ],
                ];
            } else {
                $body['query']['function_score']['query']['bool']['must'][] = [
                    'terms' => [
                        'tags' => $opts['hashtags'],
                    ],
                ];
            }
        }


        // firehose options

        if ($opts['exclude_moderated']) {
            $body['query']['function_score']['query']['bool']['must_not'][] = ['exists' => ['field' => 'moderator_guid']];
        }
       
        if ($opts['moderation_reservations']) {
            $body['query']['function_score']['query']['bool']['must_not'][] = [
                'terms' => [
                    'guid' => $opts['moderation_reservations'], 
                ],
            ];
        }

        //

        $esQuery = $algorithm->getQuery();
        if ($esQuery) {
            $body['query']['function_score']['query'] = array_merge_recursive($body['query']['function_score']['query'], $esQuery);
        }

        //

        $esScript = $algorithm->getScript();
        if ($esScript) {
            $body['query']['function_score']['functions'][] = [
                'script_score' => [
                    'script' => [
                        'source' => $esScript,
                    ],
                ],
            ];
        }

        //

        $esSort = $algorithm->getSort();
        if ($esSort) {
            $body['sort'][] = $esSort;
        }

        //

        $query = [
            'index' => $this->index,
            'type' => $opts['type'],
            'body' => $body,
            'size' => $opts['limit'],
            'from' => $opts['offset'],
        ];

        $prepared = new Prepared\Search();
        $prepared->query($query);

        $response = $this->client->request($prepared);

        $guids = [];
        foreach ($response['hits']['hits'] as $doc) {
            $guid = $doc['_source'][$this->getSourceField($opts['type'])];
            if (isset($guids[$guid])) {
                continue;
            }
            $guids[$guid] = true;
            yield (new ScoredGuid())
                ->setGuid($doc['_source'][$this->getSourceField($opts['type'])])
                ->setScore($algorithm->fetchScore($doc))
                ->setOwnerGuid($doc['_source']['owner_guid'])
                ->setTimestamp($doc['_source']['@timestamp']);
        }
    }

    private function getSourceField(string $type)
    {
        switch ($type) {
            //case 'user':
            //    return 'owner_guid';
            //    break;
            //case 'group':
            //    return 'container_guid';
            //    break;
            default:
                return 'guid';
                break;
        }
    }

    public function add(MetricsSync $metric)
    {
        $body = [];

        $key = $metric->getMetric() . ':' . $metric->getPeriod();
        $body[$key] = $metric->getCount();

        $body[$key . ':synced'] = $metric->getSynced();

        $this->pendingBulkInserts[] = [
            'update' => [
                '_id' => (string) $metric->getGuid(),
                '_index' => 'minds_badger',
                '_type' => $metric->getType(),
            ],
        ];

        $this->pendingBulkInserts[] = [
            'doc' => $body,
            'doc_as_upsert' => true,
        ];

        if (count($this->pendingBulkInserts) > 2000) { //1000 inserts
            $this->bulk();
        }

        return true;
    }

    /**
     * Run a bulk insert job (quicker).
     */
    public function bulk()
    {
        if (count($this->pendingBulkInserts) > 0) {
            $res = $this->client->bulk(['body' => $this->pendingBulkInserts]);
            $this->pendingBulkInserts = [];
        }
    }

}
