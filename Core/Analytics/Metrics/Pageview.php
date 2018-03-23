<?php


namespace Minds\Core\Analytics\Metrics;


use DateTime;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared\Search;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Interfaces\AnalyticsMetric;


class Pageview implements AnalyticsMetric
{

    private $key;
    /** @var Client */
    protected $elastic;
    protected $index;

    public function __construct($elastic = null)
    {
        $this->elastic = $elastic ?: Di::_()->get('Database\ElasticSearch');
        $this->index = "minds-metrics-" . date('m-Y', time());
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
     * @return void
     */
    public function increment()
    {
        // not applicable
    }

    /**
     * Return a set of analytics for a timespan
     * @param  int $span - eg. 3 (will return 3 units, eg 3 day, 3 months)
     * @param  string $unit - eg. day, month, year
     * @param  int $timestamp (optional) - sets the base to work off
     * @return array
     */
    public function get($span = 30, $unit = 'day', $timestamp = null)
    {
        $time = null;
        switch ($unit) {
            case "day":
                $time = (new \DateTime('midnight'))->modify("-$span days");
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
                                'action' => 'pageview'
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
                    'pageviews' => [
                        'date_histogram' => [
                            'field' => '@timestamp',
                            'interval' => $interval,
                            'min_doc_count' => 1,
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => 'cookie_id.keyword',
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

        $result = $this->elastic->request($prepared);

        $data = [];
        foreach ($result['aggregations']['pageviews']['buckets'] as $count) {
            $timestamp = $count['key'] / 1000;
            $data[] = [
                'timestamp' => $timestamp,
                'date' => $date = date('d-m-Y', $timestamp),
                'unique' => (int) $count['uniques']['value'],
                'total' => (int) $count['doc_count']
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
        return Helpers\Counters::get($this->key, "{$this->namespace}impression");
    }
}
