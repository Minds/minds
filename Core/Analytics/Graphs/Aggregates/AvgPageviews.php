<?php

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Di\Di;
use Minds\Core\Analytics\Graphs\Manager;

class AvgPageviews implements AggregateInterface
{
    /** @var Client */
    protected $client;
    /** @var abstractCacher */
    protected $cacher;
    /** @var string */
    protected $index;

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
        foreach ([
            'mau_unique',
            'mau_loggedin',
            'dau_loggedin',
            'dau_unique',
            'total_pageviews',
            'hau_unique',
            'hau_loggedin',
        ] as $key) {
            foreach ([ 'day', 'month' ] as $unit) {
                $k = Manager::buildKey([
                    'aggregate' => $opts['aggregate'] ?? 'avgpageviews',
                    'key' => $key,
                    'unit' => $unit,
                ]);
                $result[$k] = $this->fetch([ 
                    'key' => $key,
                    'unit' => $unit
                ]);
            }
        }
        return $result;
    }

    public function fetch(array $options = [])
    {
        $options = array_merge([
            'span' => 12,
            'unit' => 'month', // day / month
            'key' => null,
        ], $options);

        if (!isset($options['key'])) {
            throw new \Exception('key must be provided in the options array');
        }

        $key = $options['key'];

        $from = null;
        switch ($options['unit']) {
            case "day":
                $from = (new DateTime('midnight'))->modify("-{$options['span']} days");
                $to = (new DateTime('midnight'));
                $interval = '1d';
                break;
            case "month":
                $from = (new DateTime('midnight first day of next month'))->modify("-{$options['span']} months");
                $to = new DateTime('midnight first day of next month');
                $interval = '1M';
                break;
            default:
                throw new \Exception("{$options['unit']} is not an accepted unit");
        }

        $response = null;
        switch ($key) {
            case 'mau_unique':
                $response = $this->getMauUnique($from, $to, $interval);
                break;
            case 'mau_loggedin':
                $response = $this->getMauLoggedIn($from, $to, $interval);
                break;
            case 'dau_loggedin':
                $response = $this->getDauLoggedIn($from, $to);
                break;
            case 'dau_unique':
                $response = $this->getDauUnique($from, $to);
                break;
            case 'total_pageviews':
                $response = $this->getTotalPageviews($from, $to);
                break;
            case 'hau_unique':
                $response = $this->getHauUnique($from, $to);
                break;
            case 'hau_loggedin':
                $response = $this->getHauLoggedIn($from, $to);
                break;
        }

        return $response;
    }

    public function hasTTL(array $opts = [])
    {
        return false;
    }

    public function buildCacheKey(array $opts = [])
    {
        return "{$opts['key']}:{$opts['unit']}";
    }

    private function getMauUnique($from, $to, $interval)
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
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "avg" => [
                        "avg_bucket" => [
                            "buckets_path" => "1-bucket>1-metric"
                        ]
                    ],
                    "1-bucket" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => $interval,
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "1-metric" => [
                                "cardinality" => [
                                    "field" => "cookie_id.keyword"
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

        $response = $result['aggregations']['avg']['value'] ?? 0;

        return $response;
    }

    private function getMauLoggedIn($from, $to, $interval)
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
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "avg" => [
                        "avg_bucket" => [
                            "buckets_path" => "1-bucket>1-metric"
                        ]
                    ],
                    "1-bucket" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => $interval,
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "1-metric" => [
                                "cardinality" => [
                                    "field" => "user_guid.keyword"
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

        $response = $result['aggregations']['avg']['value'] ?? 0;

        return $response;
    }

    private function getDauLoggedIn($from, $to)
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
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "avg" => [
                        "avg_bucket" => [
                            "buckets_path" => "1-bucket>1-metric"
                        ]
                    ],
                    "1-bucket" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => "1d",
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "1-metric" => [
                                "cardinality" => [
                                    "field" => "user_guid.keyword"
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

        $response = $result['aggregations']['avg']['value'] ?? 0;

        return $response;
    }

    private function getDauUnique($from, $to)
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
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "avg" => [
                        "avg_bucket" => [
                            "buckets_path" => "1-bucket>1-metric"
                        ]
                    ],
                    "1-bucket" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => "1d",
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "1-metric" => [
                                "cardinality" => [
                                    "field" => "cookie_id.keyword"
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

        $response = $result['aggregations']['avg']['value'] ?? 0;

        return $response;
    }

    private function getHauUnique($from, $to)
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
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "avg" => [
                        "avg_bucket" => [
                            "buckets_path" => "1-bucket>1-metric"
                        ]
                    ],
                    "1-bucket" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => "1h",
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "1-metric" => [
                                "cardinality" => [
                                    "field" => "cookie_id.keyword"
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

        $response = $result['aggregations']['avg']['value'] ?? 0;

        return $response;
    }

    private function getHauLoggedIn($from, $to)
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
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "avg" => [
                        "avg_bucket" => [
                            "buckets_path" => "1-bucket>1-metric"
                        ]
                    ],
                    "1-bucket" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => "1h",
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "1-metric" => [
                                "cardinality" => [
                                    "field" => "user_guid.keyword"
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

        $response = $result['aggregations']['avg']['value'] ?? 0;

        return $response;
    }

    private function getTotalPageviews($from, $to)
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
                    "action.keyword" => [
                        "query" => "pageview"
                    ]
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
                        'must' => $must
                    ]
                ],
                "aggs" => [
                    "avg" => [
                        "avg_bucket" => [
                            "buckets_path" => "1-bucket>_count"
                        ]
                    ],
                    "1-bucket" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => "1M",
                            "min_doc_count" => 1
                        ]
                    ]
                ]
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $response = $result['aggregations']['avg']['value'] ?? 0;

        return $response;
    }
}
