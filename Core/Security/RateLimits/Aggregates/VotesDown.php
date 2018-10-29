<?php

namespace Minds\Core\Security\RateLimits\Aggregates;

use Minds\Core\Data\ElasticSearch;
use Minds\Core\Trending\Aggregates\Aggregate;

class VotesDown extends Aggregate
{
    public function get()
    {
        $cardinality_field = 'user_phone_number_hash';

        $filter = [
            'term' => ['action' => 'vote:down']
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
                            'field' => "user_guid.keyword",
                            'size' => $this->limit,
                        ],
                    ]
                ]
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $entities = [];
        foreach ($result['aggregations']['entities']['buckets'] as $entity) {
            $entities[$entity['key']] = $entity['doc_count'];
        }
        return $entities;
    }

}
