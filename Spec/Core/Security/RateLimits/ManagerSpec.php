<?php

namespace Spec\Minds\Core\Security\RateLimits;

use Minds\Core\Data\Sessions;
use Minds\Core\Security\RateLimits\Delegates\Notification;
use Minds\Core\Security\RateLimits\Delegates\Analytics;
use Minds\Core\Security\RateLimits\Manager;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;

class ManagerSpec extends ObjectBehavior
{
    private $sessions;
    private $notification;
    private $analytics;

    function let(
        Sessions $sessions,
        Notification $notification,
        Analytics $analytics
    )
    {
        $this->sessions = $sessions;
        $this->notification = $notification;
        $this->analytics = $analytics;

        $this->beConstructedWith($sessions, $notification, $analytics);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_impose_a_rate_limit(User $user)
    {
        $user->set('ratelimited_interaction:subscribe', time() + 300)
            ->shouldBeCalled();

        $user->save()
            ->shouldBeCalled();

        $this->notification->notify($user, 'ratelimited_interaction:subscribe', 300)
            ->shouldBeCalled();
        
        $this->analytics->emit($user, 'ratelimited_interaction:subscribe', 300)
            ->shouldBeCalled();

        $this->setUser($user)
            ->setInteraction('subscribe')
            ->impose();
    }

    function it_should_impose_a_rate_limit_with_a_custom_limit_period(User $user)
    {
        $user->set('ratelimited_interaction:subscribe', time() + 600)
            ->shouldBeCalled();

        $user->save()
            ->shouldBeCalled();

        $this->notification->notify($user, 'ratelimited_interaction:subscribe', 600)
            ->shouldBeCalled();

        $this->analytics->emit($user, 'ratelimited_interaction:subscribe', 600)
            ->shouldBeCalled();

        $this->setUser($user)
            ->setInteraction('subscribe')
            ->setLimitLength(600)//10 minutes
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
