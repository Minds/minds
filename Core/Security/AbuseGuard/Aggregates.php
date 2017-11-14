<?php
/**
 * Abuse Guard Aggregates
 */
namespace Minds\Core\Security\AbuseGuard;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;

class Aggregates
{

    private $start = 0;
    private $end = 0;
    private $limit = 100;

    private $aggregates = [];

    public function __construct($client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->start = time() - (60 * 10);
        $this->end = time();
    }

    public function setPeriod($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getDownVotes()
    {
        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'size' => 1, //we want just the aggregates
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'term' => [ 
                                'action' => 'vote:down' 
                            ]
                        ],
                        'must' => [
                            'range' => [
                                '@timestamp' => [
                                    'gte' => $this->start * 1000,
                                    'lte' => $this->end * 1000
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'vote:down' => [
                        'terms' => [
                            'field' => 'user_guid.keyword',
                            'size' => $this->limit
                        ]
                    ]
                ]
            ]
        ];

        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);
        $rows = [];
        if ($result) {
            foreach ($result['aggregations']['vote:down']['buckets'] as $metric) {
                $rows[] = [
                    'guid' => $metric['key'],
                    'count' => $metric['doc_count']
                ];
            }
        }

        return $rows;
    }

    public function getComments()
    {
        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'size' => 0, //we want just the aggregates
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'term' => [ 'action' => 'comment' ]
                        ],
                        'must' => [
                            'range' => [
                                '@timestamp' => [
                                    'gte' => $this->start * 1000,
                                    'lte' => $this->end * 1000
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'comments' => [
                        'terms' => [
                            'field' => 'user_guid.keyword',
                            'size' => $this->limit
                        ]
                    ]
                ]
            ]
        ];

        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);
        $rows = [];
        if ($result) {
            foreach ($result['aggregations']['comments']['buckets'] as $metric) {
                $rows[] = [
                    'guid' => $metric['key'],
                    'count' => $metric['doc_count']
                ];
            }
        }

        return $rows;
    }

    public function getPosts()
    {
        $query = [
            'index' => 'minds_badger',
            'type' => 'activity',
            'size' => 0, //we want just the aggregates
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'range' => [
                                '@timestamp' => [
                                    'gte' => $this->start * 1000,
                                    'lte' => $this->end * 1000
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'posts' => [
                        'terms' => [
                            'field' => 'owner_guid.keyword',
                            'size' => $this->limit
                        ]
                    ]
                ]
            ]
        ];

        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);
        $rows = [];
        if ($result) {
            foreach ($result['aggregations']['posts']['buckets'] as $metric) {
                $rows[] = [
                    'guid' => $metric['key'],
                    'count' => $metric['doc_count']
                ];
            }
        }

        return $rows;
    }

    public function fetch()
    {
        $this->aggregates = [
            'comments' => $this->getComments(),
            'vote:down' => $this->getDownVotes(),
            'posts' => $this->getPosts()
            ];
        return $this->aggregates;
    }

    //public function getPosts()
    //{
    //
    //}

}
