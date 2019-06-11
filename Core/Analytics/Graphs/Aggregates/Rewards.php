<?php

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Di\Di;
use Minds\Interfaces\AnalyticsMetric;
use Minds\Core\Analytics\Graphs\Manager;

class Rewards implements AggregateInterface
{
    /** @var Client */
    protected $client;

    /** @var abstractCacher */
    protected $cacher;

    /** @var string */
    protected $index;

    /** @var string */
    protected $dateFormat;

    public function __construct($client = null, $cacher = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->cacher = $cacher ?: Di::_()->get('Cache\Redis');
        $this->index = 'minds-offchain*';
    }

    /**
     * Fetch all
     * @param array $opts
     * @return array
     */
    public function fetchAll($opts = [])
    {
        $result = [];
        foreach ([ 'day', 'month' ] as $unit) {
            $k = Manager::buildKey([
                'aggregate' => $opts['aggregate'] ?? 'rewards',
                'key' => null,
                'unit' => $unit,
            ]);
            $result[$k] = $this->fetch([
                'unit' => $unit,
            ]);
        }
        return $result;
    }

    public function fetch(array $options = [])
    {
        $options = array_merge([
            'span' => 12,
            'unit' => 'month', // day / month
        ], $options);

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

        return $this->getGraph($from, $to, $interval);
    }

    private function getGraph($from, $to, $interval)
    {
        $must = [
            [
                "match_all" => (object) []
            ],
            [
                "range" => [
                    "@timestamp" => [
                        "gte" => $from->getTimestamp() * 1000,
                        "lte" => $to->getTimestamp() * 1000,
                        "format" => "epoch_millis"
                    ]
                ]
            ],
            [
                "range" => [
                    "amount" => [
                        "gte" => 0,
                        "lt" => null
                    ]
                ]
            ],
            [
                "bool" => [
                    "should" => [
                        [
                            "match_phrase" => [
                                "contract" => "offchain:reward"
                            ]
                        ],
                        [
                            "match_phrase" => [
                                "contract" => "offchain:joined"
                            ]
                        ]
                    ],
                    "minimum_should_match" => 1
                ]
            ]
        ];

        $query = [
            'index' => $this->index,
            'size' => 0,
            "stored_fields" => [
                "*"
            ],

            "docvalue_fields" => [
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must,
                    ]
                ],
                "aggs" => [
                    "histogram" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => $interval,
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "count" => [
                                "terms" => [
                                    "field" => "contract",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
                                    ]
                                ],
                                "aggs" => [
                                    "sums" => [
                                        "sum" => [
                                            "field" => "amount"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $response = [
            [
                'name' => 'Reward Transactions',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'Rewarded Tokens',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);
            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['count']['buckets'][0]['sums']['value'] ?? 0;

            $response[1]['x'][] = $date;
            $response[1]['y'][] = $count['count']['buckets'][0]['doc_count'] ?? 0;
        }

        return $response;
    }

    public function hasTTL(array $opts = [])
    {
        return false;
    }

    public function buildCacheKey(array $options = [])
    {
        return "rewards:{$options['unit']}";
    }
}
