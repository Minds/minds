<?php


namespace Minds\Core\Analytics\Aggregates;


use Minds\Core\Data\ElasticSearch\Prepared\Search;

class TopActions extends Aggregate
{
    protected $uniques = true;
    protected $term;


    public function useUniques($bool)
    {
        $this->uniques = $bool;
        return $this; 
    }

    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
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

        $aggs = [
            'counts' => [
                'terms' => [
                  'field' => "{$this->term}.keyword",
                  'size' => 50,
                ],
            ]
        ];

        if ($this->uniques) {
            $aggs['counts']['terms']['order'] = [
                'uniques' => 'desc',
            ];
            $aggs['counts']['aggs'] = [
                'uniques' => [
                    'cardinality' => [
                        'field' => 'user_phone_number_hash.keyword',
                    ],
                ]
            ];
        }

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
                'aggs' => $aggs
            ]
        ];

        $prepared = new Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $counts = [];

        foreach ($result['aggregations']['counts']['buckets'] as $count) {
            $counts[] = [
                'user_guid' => $count['key'],
                'value' => $this->uniques ? (int) $count['uniques']['value'] : (int) $count['doc_count']
            ];
        }
        return $counts;
    }
}
