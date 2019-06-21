<?php

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Di\Di;
use Minds\Core\Analytics\Graphs\Manager;

class ActiveUsers implements AggregateInterface
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

    /**
     * Fetch all
     * @param array $opts
     * @return array
     */
    public function fetchAll($opts = [])
    {
        $result = [];
        foreach ([ 'hour', 'day', 'month' ] as $unit) {
            $k = Manager::buildKey([
                'aggregate' => $opts['aggregate'] ?? 'activeusers',
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
            'userGuid' => null,
        ], $options);

        $user_guid = $options['userGuid'];

        $from = null;
        switch ($options['unit']) {
            case "hour":
                $from = (new DateTime('midnight'))->modify("-{$options['span']} hours");
                $to = (new DateTime('midnight'));
                $this->dateFormat = 'y-m-d H:i';

                return $this->getHourlyPageviews($from, $to, $user_guid);
            case "day":
                $from = (new DateTime('midnight'))->modify("-{$options['span']} days");
                $to = (new DateTime('midnight'));
                $this->dateFormat = 'y-m-d';

                return $this->getDailyPageviews($from, $to, $user_guid);
                break;
            case "month":
                $from = (new DateTime('midnight first day of next month'))->modify("-{$options['span']} months");
                $to = new DateTime('midnight first day of next month');
                $this->dateFormat = 'y-m';

                return $this->getMonthlyPageviews($from, $to, $user_guid);
                break;
            default:
                throw new \Exception("{$options['unit']} is not an accepted unit");
        }
    }

    private function getHourlyPageviews($from, $to, $user_guid)
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
                "match_phrase" => [
                    "platform.keyword" => [
                        "query" => "browser"
                    ]
                ]
            ]
        ];

        // filter by user_guid
        if ($user_guid) {
            $must[]['match'] = [
                'entity_owner_guid.keyword' => $user_guid
            ];
        }

        $query = [
            'index' => $this->index,
            'size' => 0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "histogram" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => "1h",
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "hau_logged_in" => [
                                "cardinality" => [
                                    "field" => "user_guid.keyword"
                                ]
                            ],
                            "hau_unique" => [
                                "cardinality" => [
                                    "field" => "cookie_id.keyword"
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $response = [
            [
                'name' => 'HAU (Logged In)',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'HAU (Unique)',
                'x' => [],
                'y' => []
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['hau_logged_in']['value'];

            $response[1]['x'][] = $date;
            $response[1]['y'][] = $count['hau_unique']['value'];
        }

        return $response;
    }

    private function getDailyPageviews($from, $to, $user_guid)
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
                "match_phrase" => [
                    "platform.keyword" => [
                        "query" => "browser"
                    ]
                ]
            ]
        ];

        // filter by user_guid
        if ($user_guid) {
            $must[]['match'] = [
                'entity_owner_guid.keyword' => $user_guid
            ];
        }

        $query = [
            'index' => $this->index,
            'size' => 0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "histogram" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => "1d",
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "dau_logged_in" => [
                                "cardinality" => [
                                    "field" => "user_guid.keyword"
                                ]
                            ],
                            "dau_unique" => [
                                "cardinality" => [
                                    "field" => "cookie_id.keyword"
                                ]
                            ],
                        ]
                    ]
                ],
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $response = [
            [
                'name' => 'DAU (Logged In)',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'DAU (Unique)',
                'x' => [],
                'y' => []
            ]
        ];


        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['dau_logged_in']['value'];

            $response[1]['x'][] = $date;
            $response[1]['y'][] = $count['dau_unique']['value'];
        }

        return $response;
    }

    private function getMonthlyPageviews($from, $to, $user_guid)
    {
        $must = [
            [
                "range" => [
                    "@timestamp" => [
                        "gte" => $from->getTimestamp() * 1000,
                        "lte" => $to->getTimestamp() * 1000,
                        "format" => "epoch_millis"
                    ]
                ]
            ],
        ];

        // filter by user_guid
        if ($user_guid) {
            $must[]['match'] = [
                'entity_owner_guid.keyword' => $user_guid
            ];
        }

        $query = [
            'index' => $this->index,
            'size' => 0,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "histogram" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => "1M",
                            //"min_doc_count" => 1
                        ],
                        "aggs" => [
                            "mau_logged_in" => [
                                "cardinality" => [
                                    "field" => "user_guid.keyword"
                                ]
                            ],
                           "mau_unique" => [
                                "cardinality" => [
                                    "field" => "cookie_id.keyword"
                                ]
                            ],
                            "dau" => [
                                "avg_bucket" => [
                                    "buckets_path" => "dau-bucket>dau-metric"
                                ]
                            ],
                            "dau-bucket" => [
                                "date_histogram" => [
                                    "field" => "@timestamp",
                                    "interval" => "1d",
                                    "min_doc_count" => 1
                                ],
                                "aggs" => [
                                    "dau-metric" => [
                                        "cardinality" => [
                                            "field" => "user_guid.keyword"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);
 
        $response = [
            [
                'name' => 'MAU',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'Visitors',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'Avg. DAU',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'DAU',
                'x' => [],
                'y' => []
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['mau_logged_in']['value'];

            $response[1]['x'][] = $date;
            $response[1]['y'][] = $count['mau_unique']['value'];

            $response[2]['x'][] = $date;
            $response[2]['y'][] = $count['dau']['value'];
        }

        return $response;
    }

    public function hasTTL(array $opts = [])
    {
        return false;
    }

    public function buildCacheKey(array $options = [])
    {
        return "pageviews:{$options['unit']}:{$options['userGuid']}";
    }
}
