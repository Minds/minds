<?php

namespace Minds\Core\Analytics\Graphs\Aggregates;

use DateTime;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Di\Di;
use Minds\Interfaces\AnalyticsMetric;
use Minds\Core\Analytics\Graphs\Manager;

class TotalPageviews implements AggregateInterface
{
    /** @var Client */
    protected $client;

    /** @var string */
    protected $index;

    /** @var string */
    protected $dateFormat;

    public function __construct($client = null, $config = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
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
                'aggregate' => $opts['aggregate'] ?? 'totalpageviews',
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
                    "histogram" => [
                        "date_histogram" => [
                            "field" => "@timestamp",
                            "interval" => $interval,
                            "min_doc_count" => 1
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
                'name' => 'Total Pageviews',
                'x' => [],
                'y' => []
            ]
        ];

        foreach ($result['aggregations']['histogram']['buckets'] as $count) {
            $response[0]['x'][] = date($this->dateFormat, $count['key'] / 1000);
            $response[0]['y'][] = $count['doc_count'];
        }

        return $response;
    }

    public function hasTTL(array $opts = [])
    {
        return false;
    }

    public function buildCacheKey(array $options = [])
    {
        return "total_pageviews:{$options['unit']}";
    }
}
