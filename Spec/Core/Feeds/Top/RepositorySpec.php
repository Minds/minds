<?php

namespace Spec\Minds\Core\Feeds\Top;

use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared\Update;
use Minds\Core\Feeds\Top\MetricsSync;
use Minds\Core\Feeds\Top\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    function let(Client $client)
    {
        $this->client = $client;
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
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

        $this->client->bulk(Argument::that(function($arr) {
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
