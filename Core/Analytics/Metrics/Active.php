<?php

namespace Minds\Core\Analytics\Metrics;

use DateTime;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Core;
use Minds\Core\Analytics\Timestamps;
use Minds\Interfaces\AnalyticsMetric;

/**
 * Active Metric
 */
class Active implements AnalyticsMetric
{
    private $db;
    /** @var Core\Data\ElasticSearch\Client */
    private $client;
    private $namespace = "analytics:";
    private $key;

    public function __construct($db = null, $client = null)
    {
        $this->db = $db ?: new Core\Data\Call('entities_by_time');
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');

        if (Core\Session::getLoggedinUser()) {
            $this->key = Core\Session::getLoggedinUser()->guid;
        }
    }

    /**
     * Sets the current namespace
     * @param string $namesapce
     */
    public function setNamespace($namesapce)
    {
        //$this->namespace = $namespace . ":";
    }

    /**
     * Sets the current key
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Increments metric counter
     * @return bool
     */
    public function increment()
    {
        $cacher = Core\Data\cache\factory::build('apcu');
        if ($cacher->get("{$this->namespace}active:$p:$ts:$this->key") == true) {
            return;
        }
        $this->db->insert("{$this->namespace}active:$p:$ts", array($this->key => time()));
        $cacher->set("{$this->namespace}active:$p:$ts:$this->key", time());
    }

    /**
     * Return a set of analytics for a timespan
     * @param  int $span - eg. 3 (will return 3 units, eg 3 day, 3 months)
     * @param  string $unit - eg. day, month, year
     * @param  int $timestamp (optional) - sets the base to work off
     * @return array
     */
    public function get($span = 3, $unit = 'day', $timestamp = null)
    {
        $time = null;
        switch ($unit) {
            case "day":
                $time = (new DateTime('midnight'))->modify("-$span days");
                $interval = '1d';
                break;
            case "month":
                $time = (new DateTime('midnight first day of this month'))->modify("-$span months");
                $interval = '1M';
                break;
            default:
                throw new \Exception("$unit is not an accepted unit");
        }

        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'term' => [
                                'action' => 'active'
                            ]
                        ],
                        'must' => [
                            'range' => [
                                '@timestamp' => [
                                    'gte' => $time->getTimestamp() * 1000,
                                    'lt' => time() * 1000,
                                    'format' => 'epoch_millis'
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'counts' => [
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

        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);
        
        $data = [];
        foreach ($result['aggregations']['counts']['buckets'] as $count) {
            $data[] = [
                'timestamp' => $count['key'] / 1000,
                'date' => date('d-m-Y', $count['key'] / 1000),
                'total' => (int) $count['uniques']['value']
            ];
        }

        return $data;
    }

    /**
     * Returns total metric counter
     * @return int
     */
    public function total()
    {
        return 0;
    }
}
