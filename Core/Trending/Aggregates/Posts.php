<?php
/**
 * Posts aggregates
 */
namespace Minds\Core\Trending\Aggregates;

use Minds\Core\Data\ElasticSearch;

class Posts extends Aggregate
{
    protected $multiplier = 1;

    public function get()
    {

        $field = 'owner_guid';

        $filter = [
            'term' => ['action' => 'post']
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

        if ($this->type == 'group') {
            $field = 'container_guid';
            $this->multiplier = 4;
            /*$must[]['range'] = [
                'access_id' => [
                  'gte' => 3, //would be group
                  'lt' => null,
                ]
                ];*/
        }

        $query = [
            'index' => 'minds_badger',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ],
                'aggs' => [
                    'entities' => [
                        'terms' => [
                            'size' => $this->limit,
                                'order' => [
                                'uniques' => 'desc'
                            ]
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => "$field",
                                    'precision_threshold' => 40000,
                                ]
                            ] 
                        ]
                    ]
                ]
            ]
        ];
var_dump($query);
        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);
        $result = $this->client->request($prepared);
        $entities = [];
        foreach ($result['aggregations']['entities']['buckets'] as $entity) {
            $entities[$entity['key']] = $entity['uniques']['value'] * $this->multiplier;
        }
        echo "\n\n\n\n\n";
        var_dump($entities);exit;
        return $entities;
    }
}
