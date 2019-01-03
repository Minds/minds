<?php
/**
 * Reminds aggregates
 */
namespace Minds\Core\Trending\Aggregates;

use Minds\Core\Data\ElasticSearch;

class Reminds extends Aggregate
{

    protected $multiplier = 1;

    public function get()
    {
        $filter = [
            'term' => ['action' => 'remind']
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

        $field = 'entity_guid';
        $cardinality_field = 'user_phone_number_hash';

        if ($this->type == 'group') {
            $field = 'entity_container_guid';
            $must[]['range'] = [
                'entity_access_id' => [
                  'gte' => 3, //would be group
                  'lt' => null,
                ]
            ];
        }

        //$must[]['match'] = [
        //    'rating' => 1
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
                                    'field' => "$cardinality_field.keyword"
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
