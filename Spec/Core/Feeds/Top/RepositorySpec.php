<?php

namespace Spec\Minds\Core\Feeds\Top;

use Minds\Core\Config;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared\Search;
use Minds\Core\Feeds\Top\MetricsSync;
use Minds\Core\Feeds\Top\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    /** @var Config */
    protected $config;

    function let(Client $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;

        $config->get('elasticsearch')
            ->shouldBeCalled()
            ->willReturn(['index' => 'minds']);

        $this->beConstructedWith($client, $config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_query_a_list_of_activity_guids()
    {
        $opts = [
            'type' => 'activity',
            'algorithm' => 'top',
            'period' => '1y',
            'query' => 'test'
        ];

        $this->client->request(Argument::type(Search::class))
            ->shouldBeCalled()
            ->willReturn([
                'hits' => [
                    'hits' => [
                        [
                            '_source' => [
                                'guid' => '1',
                                'owner_guid' => '1000',
                                'time_created' => 1,
                                '@timestamp' => 1000,
                            ],
                            '_score' => 100
                        ],
                        [
                            '_source' => [
                                'guid' => '2',
                                'owner_guid' => '1000',
                                'time_created' => 1,
                                '@timestamp' => 1000,
                            ],
                            '_score' => 50
                        ],
                    ]
                ]
            ]);

        $gen = $this->getList($opts);

        $gen->current()->getGuid()->shouldReturn('1');
        $gen->current()->getScore()->shouldReturn(100.0);
        $gen->next();
        $gen->current()->getGuid()->shouldReturn('2');
        $gen->current()->getScore()->shouldReturn(50.0);
    }

    function it_should_query_a_list_of_channel_guids()
    {
        $opts = [
            'type' => 'user',
            'algorithm' => 'top',
            'period' => '1y',
            'query' => 'test'
        ];

        $this->client->request(Argument::that(function ($query) {
            $query = $query->build();
            return $query['type'] === 'user' && in_array('guid', $query['body']['_source']);
        }))
            ->shouldBeCalled()
            ->willReturn([
                'hits' => [
                    'hits' => [
                        [
                            '_source' => [
                                'guid' => '1',
                                'owner_guid' => '1',
                                'time_created' => 1,
                                '@timestamp' => 1000,
                            ],
                            '_score' => 100
                        ],
                        [
                            '_source' => [
                                'guid' => '2',
                                'owner_guid' => '2',
                                'time_created' => 2,
                                '@timestamp' => 2000,
                            ],
                            '_score' => 50
                        ],
                    ]
                ]
            ]);

        $gen = $this->getList($opts);

        $gen->current()->getGuid()->shouldReturn('1');
        $gen->current()->getScore()->shouldReturn(100.0);
        $gen->next();
        $gen->current()->getGuid()->shouldReturn('2');
        $gen->current()->getScore()->shouldReturn(50.0);
    }

    function it_should_query_a_list_of_group_guids()
    {
        $opts = [
            'type' => 'group',
            'algorithm' => 'top',
            'period' => '1y',
            'query' => 'test'
        ];

        $this->client->request(Argument::that(function ($query) {
            $query = $query->build();
            return $query['type'] === 'group' && in_array('guid', $query['body']['_source']);
        }))
            ->shouldBeCalled()
            ->willReturn([
                'hits' => [
                    'hits' => [
                        [
                            '_source' => [
                                'guid' => '1',
                                'owner_guid' => '1000',
                                'time_created' => 1,
                                '@timestamp' => 1000,
                                'container_guid' => '1',
                            ],
                            '_score' => 100
                        ],
                        [
                            '_source' => [
                                'guid' => '2',
                                'owner_guid' => '1001',
                                'time_created' => 2,
                                '@timestamp' => 2000,
                                'container_guid' => '2',
                            ],
                            '_score' => 50
                        ],
                    ]
                ]
            ]);

        $gen = $this->getList($opts);

        $gen->current()->getGuid()->shouldReturn('1');
        $gen->current()->getScore()->shouldReturn(100.0);
        $gen->next();
        $gen->current()->getGuid()->shouldReturn('2');
        $gen->current()->getScore()->shouldReturn(50.0);
    }

    // Seems like yielded functions have issues with PHPSpec
    //
    // function it_should_throw_during_get_list_if_no_type()
    // {
    //     $this
    //         ->shouldThrow(new \Exception('Type must be provided'))
    //         ->duringGetList([
    //         'type' => '',
    //         'algorithm' => 'hot',
    //         'period' => '12h',
    //     ]);
    // }
    //
    // function it_should_throw_during_get_list_if_no_algorithm()
    // {
    //     $this
    //         ->shouldThrow(new \Exception('Algorithm must be provided'))
    //         ->duringGetList([
    //         'type' => 'activity',
    //         'algorithm' => '',
    //         'period' => '12h',
    //     ]);
    // }
    //
    // function it_should_throw_during_get_list_if_invalid_period()
    // {
    //     $this
    //         ->shouldThrow(new \Exception('Unsupported period'))
    //         ->duringGetList([
    //         'type' => 'activity',
    //         'algorithm' => 'hot',
    //         'period' => '!!',
    //     ]);
    // }

    function it_should_add(MetricsSync $metric)
    {
        $metric->getMetric()
            ->shouldBeCalled()
            ->willReturn('test');

        $metric->getPeriod()
            ->shouldBeCalled()
            ->willReturn('12h');

        $metric->getType()
            ->shouldBeCalled()
            ->willReturn('test');

        $metric->getCount()
            ->shouldBeCalled()
            ->willReturn(500);

        $metric->getSynced()
            ->shouldBeCalled()
            ->willReturn(100000);

        $metric->getGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->client->bulk(Argument::that(function ($arr) {
            return isset($arr['body']);
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->add($metric)
            ->shouldReturn(true);

        $this->bulk();
    }
}
