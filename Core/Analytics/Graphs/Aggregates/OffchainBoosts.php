<?php

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Di\Di;
use Minds\Core\Analytics\Graphs\Manager;

class OffchainBoosts implements AggregateInterface
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
        $this->index = $config ? $config->get('elasticsearch')['index'] : Di::_()->get('Config')->get('elasticsearch')['boost_index'];
    }

    public function hasTTL(array $opts = [])
    {
        return false;
    }

    public function buildCacheKey(array $options = [])
    {
        return "offchain:boosts:{$options['key']}:{$options['unit']}";
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
            'completed',
            'not_completed',
            'revoked',
            'rejected',
            'users_who_completed',
            'users_waiting_for_completion',
            'reclaimed_tokens',
            'impressions_served',
        ] as $key) {
            foreach ([ 'day', 'month' ] as $unit) {
                $k = Manager::buildKey([
                    'aggregate' => $opts['aggregate'] ?? 'offchainboosts',
                    'key' => $key,
                    'unit' => $unit,
                ]);
                $result[$k] = $this->fetch([
                    'key' => $key,
                    'unit' => $unit,
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


        switch ($key) {
            case 'completed':
                return $this->getCompleted($from, $to, $interval);
                break;
            case 'not_completed':
                return $this->getNotCompleted($from, $to, $interval);
                break;
            case 'revoked':
                return $this->getRevoked($from, $to, $interval);
                break;
            case 'rejected':
                return $this->getRejected($from, $to, $interval);
                break;
            case 'users_who_completed':
                return $this->getUsersWhoCompleted($from, $to, $interval);
                break;
            case 'users_waiting_for_completion':
                return $this->getUsersWaitingForCompletion($from, $to, $interval);
                break;
            case 'reclaimed_tokens':
                return $this->getReclaimedTokens($from, $to, $interval);
                break;
            case 'impressions_served':
                return $this->getImpressionsServed($from, $to, $interval);
                break;
        }
    }

    private function getCompleted($from, $to, $interval)
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
                "exists" => [
                    "field" => "@completed"
                ]
            ]
        ];

        $must_not = [
            [
                "bool" => [
                    "minimum_should_match" => 1,
                    "should" => [
                        [
                            "match_phrase" => [
                                "bid_type" => "points"
                            ]
                        ],
                        [
                            "match_phrase" => [
                                "bid_type" => "usd"
                            ]
                        ]
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
            /*"script_fields" => [
                "ReviewedToCompleted" => [
                    "script" => [
                        "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                        "lang" => "painless"
                    ]
                ],
                "CreatedToReviewed" => [
                    "script" => [
                        "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                        "lang" => "painless"
                    ]
                ],
                "CreatedToCompleted" => [
                    "script" => [
                        "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                        "lang" => "painless"
                    ]
                ]
            ],*/
            "docvalue_fields" => [
                (object) [
                    "field" => "@completed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@rejected",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@reviewed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@revoked",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                "script_fields" => [
                    "ReviewedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToReviewed" => [
                        "script" => [
                            "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                            "lang" => "painless"
                        ]
                    ]
                ],
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'must_not' => $must_not,
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
                            "boost_type" => [
                                "terms" => [
                                    "field" => "type",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
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
                'name' => 'newsfeed',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'content',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $response[0]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[0]['y'][] = $count['boost_type']['buckets'][0]['doc_count'];

            $response[1]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[1]['y'][] = $count['boost_type']['buckets'][1]['doc_count'];
        }

        return $response;
    }

    private function getNotCompleted($from, $to, $interval)
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

        $must_not = [
            [
                "exists" => [
                    "field" => "@completed"
                ]
            ],
            [
                "exists" => [
                    "field" => "@revoked"
                ]
            ],
            [
                "exists" => [
                    "field" => "@rejected"
                ]
            ]
        ];

        $query = [
            'index' => $this->index,
            'size' => 0,
            "stored_fields" => [
                "*"
            ],
            /*"script_fields" => [
                "ReviewedToCompleted" => [
                    "script" => [
                        "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                        "lang" => "painless"
                    ]
                ],
                "CreatedToReviewed" => [
                    "script" => [
                        "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                        "lang" => "painless"
                    ]
                ],
                "CreatedToCompleted" => [
                    "script" => [
                        "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                        "lang" => "painless"
                    ]
                ]
            ],*/
            "docvalue_fields" => [
                (object) [
                    "field" => "@completed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@rejected",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@reviewed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@revoked",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                "script_fields" => [
                    "ReviewedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToReviewed" => [
                        "script" => [
                            "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                            "lang" => "painless"
                        ]
                    ]
                ],
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'must_not' => $must_not,
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
                            "boost_type" => [
                                "terms" => [
                                    "field" => "type",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
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
                'name' => 'newsfeed',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'content',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $response[0]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[0]['y'][] = $count['boost_type']['buckets'][0]['doc_count'];

            $response[1]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[1]['y'][] = $count['boost_type']['buckets'][1]['doc_count'];
        }

        return $response;
    }

    private function getRevoked($from, $to, $interval)
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
                "exists" => [
                    "field" => "@revoked"
                ]
            ]
        ];

        $query = [
            'index' => $this->index,
            'size' => 0,
            "stored_fields" => [
                "*"
            ],
            /*"script_fields" => [
                "ReviewedToCompleted" => [
                    "script" => [
                        "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                        "lang" => "painless"
                    ]
                ],
                "CreatedToReviewed" => [
                    "script" => [
                        "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                        "lang" => "painless"
                    ]
                ],
                "CreatedToCompleted" => [
                    "script" => [
                        "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                        "lang" => "painless"
                    ]
                ]
            ],*/
            "docvalue_fields" => [
                (object) [
                    "field" => "@completed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@rejected",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@reviewed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@revoked",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                "script_fields" => [
                    "ReviewedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToReviewed" => [
                        "script" => [
                            "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                            "lang" => "painless"
                        ]
                    ]
                ],
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
                            "boost_type" => [
                                "terms" => [
                                    "field" => "type",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
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
                'name' => 'newsfeed',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'content',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $response[0]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[0]['y'][] = $count['boost_type']['buckets'][0]['doc_count'];

            $response[1]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[1]['y'][] = $count['boost_type']['buckets'][1]['doc_count'];
        }

        return $response;
    }

    private function getRejected($from, $to, $interval)
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
                "exists" => [
                    "field" => "@rejected"
                ]
            ]
        ];

        $query = [
            'index' => $this->index,
            'size' => 0,
            "stored_fields" => [
                "*"
            ],
            /*"script_fields" => [
                "ReviewedToCompleted" => [
                    "script" => [
                        "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                        "lang" => "painless"
                    ]
                ],
                "CreatedToReviewed" => [
                    "script" => [
                        "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                        "lang" => "painless"
                    ]
                ],
                "CreatedToCompleted" => [
                    "script" => [
                        "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                        "lang" => "painless"
                    ]
                ]
            ],*/
            "docvalue_fields" => [
                (object) [
                    "field" => "@completed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@rejected",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@reviewed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@revoked",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                "script_fields" => [
                    "ReviewedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToReviewed" => [
                        "script" => [
                            "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                            "lang" => "painless"
                        ]
                    ]
                ],
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
                            "boost_type" => [
                                "terms" => [
                                    "field" => "type",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
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
                'name' => 'newsfeed',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'content',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $response[0]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[0]['y'][] = $count['boost_type']['buckets'][0]['doc_count'];

            $response[1]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[1]['y'][] = $count['boost_type']['buckets'][1]['doc_count'];
        }

        return $response;
    }

    private function getUsersWhoCompleted($from, $to, $interval)
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
                "exists" => [
                    "field" => "@completed"
                ]
            ]
        ];

        $must_not = [
            [
                "bool" => [
                    "should" => [
                        [
                            "match_phrase" => [
                                "bid_type" => "points"
                            ]
                        ],
                        [
                            "match_phrase" => [
                                "bid_type" => "usd"
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
                    "field" => "@completed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@rejected",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@reviewed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@revoked",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                "script_fields" => [
                    "ReviewedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToReviewed" => [
                        "script" => [
                            "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                            "lang" => "painless"
                        ]
                    ]
                ],
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'must_not' => $must_not,
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
                            "boost_type" => [
                                "terms" => [
                                    "field" => "type",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
                                    ]
                                ],
                                "aggs" => [
                                    "users" => [
                                        "cardinality" => [
                                            "field" => "owner_guid"
                                        ]
                                    ]
                                ],
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
                'name' => 'newsfeed',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'content',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['boost_type']['buckets'][0]['users']['value'] ?? 0;

            $response[1]['x'][] = $date;
            $response[1]['y'][] = $count['boost_type']['buckets'][1]['users']['value'] ?? 0;
        }

        return $response;
    }

    private function getUsersWaitingForCompletion($from, $to, $interval)
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

        $must_not = [
            [
                "bool" => [
                    "should" => [
                        [
                            "match_phrase" => [
                                "bid_type" => "points"
                            ]
                        ],
                        [
                            "match_phrase" => [
                                "bid_type" => "usd"
                            ]
                        ]
                    ],
                    "minimum_should_match" => 1
                ]
            ],
            [
                "exists" => [
                    "field" => "@completed"
                ]
            ],
            [
                "exists" => [
                    "field" => "@rejected"
                ]
            ],
            [
                "exists" => [
                    "field" => "@revoked"
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
                    "field" => "@completed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@rejected",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@reviewed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@revoked",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                "script_fields" => [
                    "ReviewedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToReviewed" => [
                        "script" => [
                            "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                            "lang" => "painless"
                        ]
                    ]
                ],
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'must_not' => $must_not,
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
                            "boost_type" => [
                                "terms" => [
                                    "field" => "type",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
                                    ]
                                ],
                                "aggs" => [
                                    "users" => [
                                        "cardinality" => [
                                            "field" => "owner_guid"
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
                'name' => 'newsfeed',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'content',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['boost_type']['buckets'][0]['users']['value'] ?? 0;

            if (isset($count['boost_type']['buckets'][1])) {
                $response[1]['x'][] = $date;
                $response[1]['y'][] = $count['boost_type']['buckets'][1]['users']['value'] ?? 0;
            }
        }

        return $response;
    }

    private function getReclaimedTokens($from, $to, $interval)
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
                    "token_method" => [
                        "query" => "offchain"
                    ]
                ]
            ],
            [
                "exists" => [
                    "field" => "@completed"
                ]
            ]
        ];

        $must_not = [
            [
                "bool" => [
                    "should" => [
                        [
                            "match_phrase" => [
                                "bid_type" => "points"
                            ]
                        ],
                        [
                            "match_phrase" => [
                                "bid_type" => "usd"
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
                    "field" => "@completed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@rejected",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@reviewed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@revoked",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                "script_fields" => [
                    "ReviewedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToReviewed" => [
                        "script" => [
                            "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                            "lang" => "painless"
                        ]
                    ]
                ],
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'must_not' => $must_not,
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
                            "boost_type" => [
                                "terms" => [
                                    "field" => "type",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
                                    ]
                                ],
                                "aggs" => [
                                    "sum" => [ 
                                        "sum" => [
                                            "field" => "bid",
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $response = [
            [
                'name' => 'newsfeed',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'content',
                'x' => [],
                'y' => [],
            ]
        ];
 
        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['boost_type']['buckets'][0]['sum']['value'] ?? 0;

            if (isset($count['boost_type']['buckets'][1])) {
                $response[1]['x'][] = $date;
                $response[1]['y'][] = $count['boost_type']['buckets'][1]['sum']['value'] ?? 0;
            }
        }

        return $response;
    }

    private function getImpressionsServed($from, $to, $interval)
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
                "exists" => [
                    "field" => "@completed"
                ]
            ]
        ];

        $must_not = [
            [
                "bool" => [
                    "should" => [
                        [
                            "match_phrase" => [
                                "bid_type" => "usd"
                            ]
                        ],
                        [
                            "match_phrase" => [
                                "bid_type" => "points"
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
                    "field" => "@completed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@rejected",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@reviewed",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@revoked",
                    "format" => "date_time"
                ],
                (object) [
                    "field" => "@timestamp",
                    "format" => "date_time"
                ]
            ],
            'body' => [
                "script_fields" => [
                    "ReviewedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@reviewed'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToReviewed" => [
                        "script" => [
                            "inline" => "(doc['@reviewed'].value.millis -doc['@timestamp'].value.millis)",
                            "lang" => "painless"
                        ]
                    ],
                    "CreatedToCompleted" => [
                        "script" => [
                            "inline" => "(doc['@completed'].value.millis -doc['@timestamp'].value.millis) ",
                            "lang" => "painless"
                        ]
                    ]
                ],
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'must_not' => $must_not,
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
                            "boost_type" => [
                                "terms" => [
                                    "field" => "type",
                                    "size" => 5,
                                    "order" => [
                                        "_count" => "desc"
                                    ]
                                ],
                                "aggs" => [
                                    "users" => [
                                        "cardinality" => [
                                            "field" => "impressions"
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
                'name' => 'newsfeed',
                'x' => [],
                'y' => [],
            ],
            [
                'name' => 'content',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['boost_type']['buckets'][0]['users']['value'] ?? 0;

            if (isset($count['boost_type']['buckets'][1])) {
                $response[1]['x'][] = $date;
                $response[1]['y'][] = $count['boost_type']['buckets'][1]['users']['value'] ?? 0;
            }
        }

        return $response;
    }
}
