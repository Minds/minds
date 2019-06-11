<?php

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared\Search;
use Minds\Core\Di\Di;
use Minds\Interfaces\AnalyticsMetric;
use Minds\Core\Analytics\Graphs\Manager;

class Votes implements AggregateInterface
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
                'aggregate' => $opts['aggregate'] ?? 'votes',
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

        $userGuid = $options['userGuid'];

        $from = null;
        switch ($options['unit']) {
            case "hour":
                $from = (new DateTime('midnight'))->modify("-{$options['span']} hours");
                $to = (new DateTime('midnight'));
                $interval = '1h';
                $this->dateFormat = 'y-m-d H:i';
                break;
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

        $query = [
            'index' => $this->index,
            'size' => 0,
            'type' => 'action',
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
                                'bool' => [
                                    'should' => [
                                        [
                                            'match_phrase' => [
                                                'action.keyword' => 'vote:up'
                                            ]
                                        ],
                                        [
                                            'match_phrase' => [
                                                'action.keyword' => 'vote:down'
                                            ]
                                        ]
                                    ],
                                    'minimum_should_match' => 1
                                ]
                            ],
                        ]
                    ]
                ],
                'aggs' => [
                    'votes' => [
                        'date_histogram' => [
                            'field' => '@timestamp',
                            'interval' => $interval,
                            'min_doc_count' => 1,
                        ],
                        "aggs" => [
                            "uniques" => [
                                "cardinality" => [
                                    "field" => "user_guid.keyword"
                                ]
                            ]
                        ]
                    ]

                ]

            ]
        ];

        if ($userGuid) {
            $query['body']['query']['bool']['must'][] = [
                'match' => [
                    'entity_owner_guid.keyword' => $userGuid
                ]
            ];
        }

        $prepared = new Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $response = [
            [
                'name' => 'Votes',
                'x' => [],
                'y' => []
            ]
        ];

        if (!$userGuid) {
            $response[] = [
                'name' => 'Number of Voting Users',
                'x' => [],
                'y' => []
            ];
        }


        foreach ($result['aggregations']['votes']['buckets'] as $count) {
            $date = date($this->dateFormat, $count['key'] / 1000);

            $response[0]['x'][] = $date;
            $response[0]['y'][] = $count['doc_count'];

            if (!$userGuid) {
                $response[1]['x'][] = $date;
                $response[1]['y'][] = $count['uniques']['value'];
            }
        }

        return $response;
    }

    public function hasTTL(array $opts = [])
    {
        return isset($opts['userGuid']);
    }

    public function buildCacheKey(array $opts = [])
    {
        return "votes:{$opts['unit']}:{$opts['userGuid']}";
    }
}
