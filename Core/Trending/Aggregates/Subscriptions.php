<?php
/**
 * Subscriptions aggregates
 */
namespace Minds\Core\Trending\Aggregates;

use Minds\Core\Data\ElasticSearch;

class Subscriptions extends Aggregate
{

    protected $multiplier = 0;

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
                            'field' => 'entity_guid.keyword',
                            'size' => $this->limit,
                            'order' => [
                                'uniques' => 'desc',
                            ],
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => 'user_phone_number_hash.keyword',
                                    'precision_threshold' => 40000,
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

        $entities = [];
        foreach ($result['aggregations']['entities']['buckets'] as $entity) {
            $entities[$entity['key']] = $entity['uniques']['value'] * $this->multiplier;
        }
        return $entities;
    }

}
