<?php

/**
 * Amount of new subscribers a given user had per month
 */

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared\Search;
use Minds\Core\Di\Di;
use Minds\Core\Analytics\Graphs\Manager;

class Subscribers implements AggregateInterface
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
                'aggregate' => $opts['aggregate'] ?? 'subscribers',
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

        if (!isset($options['userGuid'])) {
            throw new \Exception('userGuid must be set in the options parameter');
        }
        $userGuid = $options['userGuid'];

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

        $query = [
            'index' => 'minds-metrics-*',
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
                        'filter' => [
                            'term' => [
                                'action' => 'subscribe'
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
                                    'entity_guid' => $userGuid
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'subscribers' => [
                        'date_histogram' => [
                            'field' => '@timestamp',
                            'interval' => $interval,
                            'min_doc_count' => 1,
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => 'user_guid.keyword',
                                    'precision_threshold' => 40000
                                ]
                            ]
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
                'name' => 'Subscribers',
                'x' => [],
                'y' => [],
            ]
        ];

        foreach ($result['aggregations']['subscribers']['buckets'] as $count) {
            $response[0]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[0]['y'][] = $count['doc_count'];
        }


        return $response;
    }

    public function hasTTL(array $opts = [])
    {
        return true;
    }

    public function buildCacheKey(array $options = [])
    {
        if (!isset($options['userGuid'])) {
            throw new \Exception('userGuid must be set in the options parameter');
        }
        $userGuid = $options['userGuid'];

        return "subscribers:{$userGuid}:{$options['unit']}";
    }
}
