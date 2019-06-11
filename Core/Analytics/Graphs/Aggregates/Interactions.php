<?php

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared\Count;
use Minds\Core\Data\ElasticSearch\Prepared\Search;
use Minds\Core\Di\Di;
use Minds\Core\Analytics\Graphs\Manager;

class Interactions implements AggregateInterface
{
    /** @var Client */
    protected $client;
    /** @var abstractCacher */
    protected $cacher;
    /** @var string */
    protected $index;
    /** @var string */
    protected $dateFormat;

    public function __construct($client = null, $cacher = null, $config = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->cacher = $cacher ?: Di::_()->get('Cache\Redis');
        $this->index = $config ? $config->get('elasticsearch')['index'] : Di::_()->get('Config')->get('elasticsearch')['metrics_index'] . '-*';
    }

    public function hasTTL(array $opts = [])
    {
        return true;
    }

    public function fetch(array $options = [])
    {
        $options = array_merge([
            'span' => 1,
            'unit' => 'day', // day / month
            'key' => null,
            'userGuid' => null,
        ], $options);

        if (!isset($options['userGuid'])) {
            throw new \Exception('userGuid must be set in the options parameter');
        }
        $userGuid = $options['userGuid'];

        if (!isset($options['key'])) {
            throw new \Exception('key must be set in the options parameter');
        }
        $key = $options['key'];

        $from = null;
        switch ($options['unit']) {
            case "day":
                $from = (new DateTime('midnight'))->modify("-{$options['span']} days");
                $to = (new DateTime('midnight'));
                $interval = '1d';
                $this->dateFormat = 'y-m-d';
                break;
            case "month":
                $from = (new DateTime('midnight first day of next month'))->modify("-{$options['span']} months");
                $to = new DateTime('midnight first day of next month');
                $interval = '1M';
                $this->dateFormat = 'y-m';
                break;
            default:
                throw new \Exception("{$options['unit']} is not an accepted unit");
        }

        $result = null;
        $boost = strpos($key, 'boost') !== false;

        if (strpos($key, 'totals') !== false) {
            $result = $this->getTotals($from, $to, $boost, $userGuid);
        } else {
            $result = $this->getMetrics($from, $to, $boost, $userGuid, $interval);
        }

        return $result;
    }

    public function buildCacheKey(array $options = [])
    {
        if (!isset($options['userGuid'])) {
            throw new \Exception('userGuid must be set in the options parameter');
        }
        if (!isset($options['key'])) {
            throw new \Exception('key must be set in the options parameter');
        }

        return "interactions:{$options['key']}:{$options['unit']}:{$options['userGuid']}";
    }

    private function getTotals($from, $to, $boost, $userGuid)
    {
        return [
            [
                'values' => [
                    $this->getTotal('vote:up', $from, $to, $boost, $userGuid),
                    $this->getTotal('vote:down', $from, $to, $boost, $userGuid),
                    $this->getTotal('comment', $from, $to, $boost, $userGuid),
                    $this->getTotal('remind', $from, $to, $boost, $userGuid),
                ],
                'labels' => [
                    'Vote Up', 'Vote Down', 'Comment', 'Remind'
                ]
            ]
        ];
    }

    private function getTotal($action, $from, $to, $boost, $userGuid)
    {
        $query = [
            'index' => $this->index,
            'type' => 'action',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'term' => [
                                'action' => $action
                            ]
                        ],
                        'must_not' => [
                            'term' => [
                                'user_guid' => $userGuid // don't count self interactions
                            ]
                        ],
                        'must' => [
                            [
                                'match_all' => (object) []
                            ],
                            [
                                'range' => [
                                    '@timestamp' => [
                                        'gte' => $from->getTimestamp() * 1000,
                                        'lte' => $to->getTimestamp() * 1000,
                                        'format' => 'epoch_millis'
                                    ]
                                ]
                            ],
                            [
                                'match' => [
                                    'entity_owner_guid' => $userGuid
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if ($boost) {
            $query['body']['query']['bool']['must'][] = [
                'exists' => [
                    'field' => 'client_meta_campaign'
                ]
            ];
        }

        $prepared = new Count();
        $prepared->query($query);

        $result = $this->client->request($prepared);
        return $result['count'] ?? 0;
    }

    private function getMetrics($from, $to, $boost, $userGuid, $interval)
    {
        $query = [
            'index' => $this->index,
            'size' => 0,
            'type' => 'action',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            [
                                'terms' => [
                                    'action' => ['vote:up', 'vote:down', 'comment', 'remind']
                                ]
                            ]
                        ],
                        'must_not' => [
                            'term' => [
                                'user_guid' => $userGuid // don't count self interactions
                            ]
                        ],
                        'must' => [
                            [
                                'match_all' => (object) []
                            ],
                            [
                                'range' => [
                                    '@timestamp' => [
                                        'gte' => $from->getTimestamp() * 1000,
                                        'lte' => $to->getTimestamp() * 1000,
                                        'format' => 'epoch_millis'
                                    ]
                                ]
                            ],
                            [
                                'match' => [
                                    'entity_owner_guid' => $userGuid
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'interactions' => [
                        'date_histogram' => [
                            'field' => '@timestamp',
                            'interval' => $interval,
                            'min_doc_count' => 1,
                        ]
                    ]

                ]

            ]
        ];

        $prepared = new Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $response = [
            [
                'name' => 'Interactions',
                'x' => [],
                'y' => []
            ]
        ];

        foreach ($result['aggregations']['interactions']['buckets'] as $count) {
            $response[0]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[0]['y'][] = $count['doc_count'];
        }

        if ($boost) {
            $query['body']['query']['bool']['must'][] = [
                'exists' => [
                    'field' => 'client_meta_campaign'
                ]
            ];

            $prepared = new Search();
            $prepared->query($query);

            $boostResult = $this->client->request($prepared);

            $response[] = [
                [
                    'name' => 'Boost Interactions',
                    'x' => [],
                    'y' => []
                ]
            ];

            foreach ($boostResult['aggregations']['interactions']['buckets'] as $count) {
                $response[1]['x'][] = date($this->dateFormat, $count['key'] / 1000);
                $response[1]['y'][] = $count['doc_count'];
            }

        }
        return $response;
    }
}
