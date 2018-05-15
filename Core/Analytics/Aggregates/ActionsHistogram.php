<?php
/**
 * A date histogram of actions, total or filtered by user
 */
namespace Minds\Core\Analytics\Aggregates;

use Minds\Core\Data\ElasticSearch;

class ActionsHistogram extends Aggregate
{

    protected $multiplier = 2;

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

        if ($this->user) {
            //nasty hack for subscribe... @todo: find a better solution
            if ($this->action == 'subscribe' || $this->action == 'referral') {
                $must[]['match'] = [
                    'entity_guid.keyword' => $this->user->guid
                ];
            } else {
                $must[]['match'] = [
                    'entity_owner_guid.keyword' => $this->user->guid
                ];
            }
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
                'aggs' => [
                    'counts' => [
                        'date_histogram' => [ 
                            'field' => '@timestamp',
                            'interval' => $this->interval,
                            'order' => [
                                'uniques' => 'desc'
                            ]
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => 'user_phone_number_hash.keyword',
                                    'precision_threshold' => 40000
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

        $counts = [];
        foreach ($result['aggregations']['counts']['buckets'] as $count) {
            $counts[$count['key']] = (int) $count['uniques']['value'];
        }
        return $counts;
    }

}
