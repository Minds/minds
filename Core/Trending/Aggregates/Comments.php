<?php
/**
 * Comment aggregates
 */
namespace Minds\Core\Trending\Aggregates;

use Minds\Core\Data\ElasticSearch;

class Comments extends Aggregate
{

    protected $multiplier = 2;

    public function get()
    {
        $field = 'entity_guid';
        $cardinality_field = 'user_phone_number_hash';

        $filter = [
            'term' => ['action' => 'comment']
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

        if ($this->type && $this->type != 'group') {
            $must[]['match'] = [
                'entity_type' => $this->type
            ];
        }

        if ($this->subtype) {
            $must[]['match'] = [
                'entity_subtype' => $this->subtype
            ];
        }

        if ($this->type == 'group') {
            $must[]['range'] = [
                'entity_access_id' => [
                    'gt' => 2, //would be group
                ]
            ];
            $field = 'entity_container_guid';
            $this->multiplier = 4;
        }

        //$must[]['match'] = [
        //    'rating' => $this->rating
        //];

        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
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
                            'field' => "$field.keyword", 
                            'size' => $this->limit,
                            'order' => [
                                'uniques' => 'desc'
                            ]
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => "$cardinality_field.keyword",
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
