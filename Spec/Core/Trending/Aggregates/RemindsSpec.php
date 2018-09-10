<?php

namespace Spec\Minds\Core\Trending\Aggregates;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\ElasticSearch;
use Minds\Core\Data\ElasticSearch\Client;

class RemindsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Trending\Aggregates\Reminds');
    }

    function it_should_return_comments_with_new_score(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::type('Minds\\Core\\Data\\ElasticSearch\\Prepared\\Search'))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'entities' => [
                        'buckets' => [
                            [ 
                                'key' => 123,
                                'doc_count' => 50,
                                'uniques' => [
                                    'value' => 50,
                                ],
                            ],
                            [ 
                                'key' => 456,
                                'doc_count' => 25,
                                'uniques' => [
                                    'value' => 25,
                                ],
                            ],
                        ]
                    ]
                ]
            ]);

        $this->get()->shouldReturn([
            123 => 200,
            456 => 100
        ]);
    }


    function it_should_return_remind_in_groups(Client $client)
    {
        $this->beConstructedWith($client);
        $this->setType('group');
        $this->setFrom('1');
        $this->setTo('1');
        $this->setLimit(1);
        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query([
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'term' => [
                                'action' => 'remind'
                            ]
                        ],
                        'must' => [
                            [
                                'range' => [
                                    '@timestamp' => [
                                        'gte' => '1',
                                        'lte' => '1'
                                    ]
                                ],

                            ],
                            [
                                'range' => [
                                    'entity_access_id' => [
                                        'gte' => 3,
                                        'lt' => null,
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'entities' => [
                        'terms' => [ 
                            'field' => "entity_container_guid.keyword",
                            'size' => 1,
                            'order' => [ 'uniques' => 'desc' ],
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => "user_phone_number_hash.keyword"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $client->request($prepared)
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'entities' => [
                        'buckets' => [
                            [ 
                                'key' => 123,
                                'doc_count' => 50,
                                'uniques' => [
                                    'value' => 50,
                                ],
                            ],
                            [ 
                                'key' => 456,
                                'doc_count' => 25,
                                'uniques' => [
                                    'value' => 25,
                                ],
                            ],
                        ]
                    ]
                ]
            ]);

        $this->get()->shouldReturn([
            123 => 200,
            456 => 100
        ]);
    }


    function it_should_return_remind_when_is_not_a_group(Client $client)
    {
        $this->beConstructedWith($client);
        $this->setType('other');
        $this->setFrom('1');
        $this->setTo('1');
        $this->setLimit(1);
        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query([
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'term' => [
                                'action' => 'remind'
                            ]
                        ],
                        'must' => [
                            [
                                'range' => [
                                    '@timestamp' => [
                                        'gte' => '1',
                                        'lte' => '1'
                                    ]
                                ],

                            ],
                            [
                                'match' => [
                                    'entity_type' => 'other'
                                ]
                            ]
                        ]
                    ]
                ],
                'aggs' => [
                    'entities' => [
                        'terms' => [ 
                            'field' => "entity_guid.keyword",
                            'size' => 1,
                            'order' => [ 'uniques' => 'desc' ],
                        ],
                        'aggs' => [
                            'uniques' => [
                                'cardinality' => [
                                    'field' => "user_phone_number_hash.keyword"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $client->request($prepared)
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'entities' => [
                        'buckets' => [
                            [ 
                                'key' => 123,
                                'doc_count' => 50,
                                'uniques' => [
                                    'value' => 50,
                                ],
                            ],
                            [ 
                                'key' => 456,
                                'doc_count' => 25,
                                'uniques' => [
                                    'value' => 25,
                                ],
                            ],
                        ]
                    ]
                ]
            ]);

        $this->get()->shouldReturn([
            123 => 200,
            456 => 100
        ]);
    }

}
