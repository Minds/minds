<?php
/**
 * Subscriptions aggregates
 */
namespace Minds\Core\Trending\Aggregates;

use Minds\Core\Data\ElasticSearch;

class Subscribe extends Aggregate
{

    protected $multiplier = 1;

    public function get()
    {
        $filter = [ 
            'term' => [
                'action' => 'subscribe'
            ]
        ];

        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'size' => 1, //we want just the aggregates
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => $filter,
                        'must' => [
                            'range' => [
                                '@timestamp' => [
                                    'gte' => $this->from,
                                    'lte' => $this->to
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'entities' => [
                        'terms' => [ 
                            'field' => 'user_guid.keyword',
                            'size' => $this->limit,
                        ],
                    ]
                ]
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);
        $rows = [];
        foreach ($result['aggregations']['entities']['buckets'] as $row) {
            $rows[$row['key']] = $row['doc_count'] * $this->multiplier;
        }
        return $rows;
    }

}
