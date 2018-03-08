<?php
/**
 * Votes aggregates
 */
namespace Minds\Core\Trending\Aggregates;

use Minds\Core\Data\ElasticSearch;

class Votes extends Aggregate
{

    protected $multiplier = 2;

    public function get()
    {
        $filter = [ 
            'term' => [
                'action' => 'vote:up'
            ]
        ];

        $must = [
            [
                'range' => [
                '@timestamp' => [
                    'gte' => $this->from,
                    'lte' => $this->to
                    ]
                ]
            ]
        ];
        
        if ($this->type) {
            $must[]['match'] = [
                'entity_type' => $this->type
            ];
        }

        if ($this->subtype) {
            $must[]['match'] = [
                'entity_subtype' => $this->subtype
            ];
        }

        $must[]['match'] = [
            'rating' => 1
        ];

        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'size' => 0, //we want just the aggregates
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => $filter,
                        'must' => $must
                    ]
                ],
                'aggs' => [
                    'entities' => [
                        'terms' => [ 
                            'field' => 'entity_guid.keyword',
                            'size' => $this->limit,
                            'order' => [ 'uniques' => 'DESC' ],
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => 'user_guid.keyword'
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
