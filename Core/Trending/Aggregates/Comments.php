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
                            'size' => $this->limit
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
            $entities[$entity['key']] = $entity['doc_count'] * $this->multiplier;
        }
        return $entities;
    }

}
