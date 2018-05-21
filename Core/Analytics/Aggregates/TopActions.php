<?php


namespace Minds\Core\Analytics\Aggregates;


use Minds\Core\Data\ElasticSearch\Prepared\Search;

class TopActions extends Aggregate
{
    protected $term;

    function setTerm($term)
    {
        $this->term = $term;
    }

    public function get()
    {
        $filter = [
            'term' => [
                'action' => $this->action
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

        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'size' => 1, //we want just the aggregates
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => $filter,
                        'must' => $must
                    ]
                ],
                'aggs' => [
                    'counts' => [
                        'terms' => [
                          'field' => "{$this->term}.keyword",
                          'size' => 50,
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => 'user_guid.keyword',
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

        $result = $this->client->request($prepared);

        $counts = [];

        foreach ($result['aggregations']['counts']['buckets'] as $count) {
            $counts[] = [
                'user_guid' => $count['key'],
                'value' => (int) $count['doc_count']
            ];
        }
        return $counts;
    }
}
