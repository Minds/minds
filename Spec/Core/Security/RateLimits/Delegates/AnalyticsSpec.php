<?php

namespace Spec\Minds\Core\Security\RateLimits\Delegates;

use Minds\Core\Security\RateLimits\Delegates\Analytics;
use Minds\Core\Analytics\Metrics\Event;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AnalyticsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Analytics::class);
    }

    function it_should_emit_event(User $user, Event $event)
    {
        $this->beConstructedWith($event);

        $user->getGUID()
            ->shouldBeCalled()
            ->willReturn(123);

        $user->getPhoneNumberHash()
            ->willReturn('phonehash');

        $event->setType('action')
            ->shouldBeCalled()
            ->willReturn($event);
        $event->setProduct('platform')
            ->shouldBeCalled()
            ->willReturn($event);
        $event->setUserGuid((string) 123)
            ->shouldBeCalled()
            ->willReturn($event);
        $event->setUserPhoneNumberHash('phonehash')
            ->shouldBeCalled()
            ->willReturn($event);
        $event->setAction("ratelimit")
            ->shouldBeCalled()
            ->willReturn($event);
        $event->setRatelimitKey('metricname')
            ->shouldBeCalled()
            ->willReturn($event);
        $event->setRatelimitPeriod(300)
            ->shouldBeCalled()
            ->willReturn($event);

        $event->push()
            ->shouldBeCalled();
        
        $this->emit($user, 'metricname', 300);
    }

}
