<?php

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Di\Di;
use Minds\Core\Analytics\Graphs\Manager;

class UserSegments implements AggregateInterface
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
        $this->index = 'minds-kite';
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
            null,
            'avg',
        ] as $key) {
            foreach ([ 'day', 'month' ] as $unit) {
                $k = Manager::buildKey([
                    'aggregate' => $opts['aggregate'] ?? 'usersegments',
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

        $response = null;

        if ($key && $key == 'avg') {
            $response = $this->getAverages($from, $to, $interval);
        } else {
            $response = $this->getGraph($from, $to, $interval);
        }

        return $response;
    }

    public function hasTTL(array $opts = [])
    {
        return false;
    }

    public function buildCacheKey(array $opts = [])
    {
        return "usersegments:{$opts['key']}:{$opts['unit']}";
    }

    private function getAverages($from, $to, $interval)
    {
        $must = [
            [
                "match_all" => (object) []
            ],
            [
                "range" => [
                    "reference_date" => [
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
                    "field" => "reference_date",
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
                    "states" => [
                        "terms" => [
                            "field" => "state",
                            "size" => 6,
                            "order" => [
                                "_key" => "desc"
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
                                    "field" => "reference_date",
                                    "interval" => $interval,
                                    "min_doc_count" => 1
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

        $response = [];

        foreach ($result['aggregations']['states']['buckets'] as $count) {
            $response[$count['key']] = $count['avg']['value'] ?? 0;
        }

        return $response;
    }

    private function getGraph($from, $to, $interval)
    {
        $must = [
            [
                "match_all" => (object) []
            ],
            [
                "range" => [
                    "reference_date" => [
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
                    "field" => "reference_date",
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
                    "histogram" => [
                        "date_histogram" => [
                            "field" => "reference_date",
                            "interval" => $interval,
                            "min_doc_count" => 1
                        ],
                        "aggs" => [
                            "states" => [
                                "terms" => [
                                    "field" => "state",
                                    "size" => 6,
                                    "order" => [
                                        "_count" => "desc"
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
                'name' => 'Curious',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'Casual',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'Core',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'Cold',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'Resurrected',
                'x' => [],
                'y' => []
            ],
            [
                'name' => 'New',
                'x' => [],
                'y' => []
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['states']['buckets'][0]['doc_count'];

            $response[1]['x'][] = $date;
            $response[1]['y'][] = $count['states']['buckets'][1]['doc_count'];;

            $response[2]['x'][] = $date;
            $response[2]['y'][] = $count['states']['buckets'][2]['doc_count'];;

            $response[3]['x'][] = $date;
            $response[3]['y'][] = $count['states']['buckets'][3]['doc_count'];;

            $response[4]['x'][] = $date;
            $response[4]['y'][] = $count['states']['buckets'][4]['doc_count'];;

            $response[5]['x'][] = $date;
            $response[5]['y'][] = $count['states']['buckets'][5]['doc_count'];;

        }

        return $response;
    }
}
