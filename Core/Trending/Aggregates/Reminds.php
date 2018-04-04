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
        $field = 'entity_guid';

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
                                    'field' => 'user_phone_numner_hash.keyword'
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
