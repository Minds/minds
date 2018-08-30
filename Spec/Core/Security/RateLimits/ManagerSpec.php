<?php

namespace Spec\Minds\Core\Security\RateLimits;

use Minds\Core\Security\RateLimits\Manager;
use Minds\Core\Security\RateLimits\Delegates\Notification;
use Minds\Entities\User;
use Minds\Core\Data\Sessions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_impose_a_rate_limit(User $user, Sessions $sessions, Notification $notification)
    {
        $this->beConstructedWith($sessions, $notification);

        $user->set('ratelimited_interaction:subscribe', time() + 300)
            ->shouldBeCalled();

        $user->get('guid')
            ->shouldbeCalled()
            ->willReturn(10001);
    
        $user->save()
            ->shouldBeCalled();

        $sessions->syncRemote(10001, $user)
            ->shouldBeCalled();

        $notification->notify($user, 'ratelimited_interaction:subscribe')
            ->shouldBeCalled();

        $this->setUser($user)
            ->setInteraction('subscribe')
            ->impose();
    }

    function it_should_impose_a_rate_limit_with_a_custom_limit_period(User $user, Sessions $sessions, Notification $notification)
    {
        $this->beConstructedWith($sessions, $notification);

        $user->set('ratelimited_interaction:subscribe', time() + 600)
            ->shouldBeCalled();

        $user->get('guid')
            ->shouldbeCalled()
            ->willReturn(10002);
    
        $user->save()
            ->shouldBeCalled();

        $sessions->syncRemote(10002, $user)
            ->shouldBeCalled();

        $notification->notify($user, 'ratelimited_interaction:subscribe')
            ->shouldBeCalled();

        $this->setUser($user)
            ->setInteraction('subscribe')
            ->setLimitLength(600) //10 minutes
            ->impose();
    }

    function it_should_return_false_if_no_rate_limit(User $user)
    {
        $this->setUser($user)
            ->setInteraction('subscribe');
        
        $this->isLimited()
            ->shouldBe(false);
    }

    function it_should_return_false_if_rate_limit_past_period(User $user)
    {
        $this->setUser($user)
            ->setInteraction('subscribe');
        
        $this->isLimited()
            ->shouldBe(false);
    }

    function it_should_return_true_if_rate_limited_impose(User $user)
    {
        $user->get('ratelimited_interaction:subscribe')
            ->shouldBeCalled()
            ->willReturn(time() + 60); //1 minute

        $this->setUser($user)
            ->setInteraction('subscribe');
    
        $this->isLimited()
            ->shouldBe(true);
    }

}
