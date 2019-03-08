<?php

namespace Spec\Minds\Core\Data;

use Minds\Common\Repository\Response;
use Minds\Core\Data\cache\Redis;
use Minds\Core\Data\SortedSet;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Mocks\Redis as RedisServer;

class SortedSetSpec extends ObjectBehavior
{
    /** @var Redis */
    protected $redis;

    /** @var RedisServer */
    protected $redisServer;

    function let(Redis $redis, RedisServer $redisServer)
    {
        $this->redis = $redis;
        $this->redisServer = $redisServer;
        $this->beConstructedWith($redis);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SortedSet::class);
    }

    function it_should_not_throttled_when_not_specified()
    {
        $this->redis->forReading()
            ->shouldNotBeCalled();

        $this
            ->setKey('phpspec')
            ->setThrottle(null)
            ->isThrottled()
            ->shouldReturn(false);
    }

    function it_should_throttle_if_within_timeframe()
    {
        $this->redis->forReading()
            ->shouldBeCalled()
            ->willReturn($this->redis);

        $this->redis->get('SortedSet$ts:phpspec')
            ->shouldBeCalled()
            ->willReturn(time() - 1);

        $this
            ->setKey('phpspec')
            ->setThrottle(10)
            ->isThrottled()
            ->shouldReturn(true);
    }

    function it_should_not_throttle_if_outside_timeframe()
    {
        $this->redis->forReading()
            ->shouldBeCalled()
            ->willReturn($this->redisServer);

        $this->redisServer->get('SortedSet$ts:phpspec')
            ->shouldBeCalled()
            ->willReturn(time() - 15);

        $this
            ->setKey('phpspec')
            ->setThrottle(10)
            ->isThrottled()
            ->shouldReturn(false);
    }

    function it_should_clean()
    {
        $this->redis->forWriting()
            ->shouldBeCalled()
            ->willReturn($this->redisServer);

        $this->redisServer->del('SortedSet:phpspec')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->redisServer->set('SortedSet$ts:phpspec', Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setKey('phpspec')
            ->clean()
            ->shouldReturn($this);
    }

    function it_should_set_a_ttl()
    {
        $this->redis->forWriting()
            ->shouldBeCalled()
            ->willReturn($this->redisServer);

        $this->redisServer->expire('SortedSet:phpspec', 360)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->redisServer->expire('SortedSet$ts:phpspec', 360)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setKey('phpspec')
            ->expiresIn(360);
    }

    function it_should_add_an_element()
    {
        $this->redis->forWriting()
            ->shouldBeCalled()
            ->willReturn($this->redisServer);

        $this->redisServer->zAdd('SortedSet:phpspec', 5, 'foobar')
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setKey('phpspec')
            ->add(5, 'foobar');
    }

    function it_should_add_an_element_lazily()
    {
        $this->redis->forWriting()
            ->shouldBeCalled()
            ->willReturn($this->redisServer);

        $this->redisServer->zAdd('SortedSet:phpspec', 5, 'foobar')
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setKey('phpspec')
            ->lazyAdd(5, 'foobar')
            ->flush(0);
    }

    function it_should_fetch_elements()
    {
        $this->redis->forReading()
            ->shouldBeCalled()
            ->willReturn($this->redisServer);

        $this->redisServer->zRange('SortedSet:phpspec', 3, 7)
            ->shouldBeCalled()
            ->willReturn([ 'foobar' ]);

        $this
            ->setKey('phpspec')
            ->fetch(5, 3)
            ->shouldReturnAnInstanceOf(Response::class);
    }
}
