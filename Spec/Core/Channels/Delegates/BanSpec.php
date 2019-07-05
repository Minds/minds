<?php

namespace Spec\Minds\Core\Channels\Delegates;

use Minds\Core\Channels\Delegates\Ban;
use Minds\Core\Events\EventsDispatcher;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BanSpec extends ObjectBehavior
{
    /** @var EventsDispatcher */
    protected $eventsDispatcher;

    function let(EventsDispatcher $eventsDispatcher)
    {
        $this->beConstructedWith($eventsDispatcher);
        $this->eventsDispatcher = $eventsDispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Ban::class);
    }

    function it_should_ban(User $user)
    {
        $user->set('ban_reason', 'phpspec')
            ->shouldBeCalled();

        $user->set('banned', 'yes')
            ->shouldBeCalled();

        $user->set('code', '')
            ->shouldBeCalled();

        $user->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->eventsDispatcher->trigger('ban', 'user', $user)
            ->shouldBeCalled();

        $this
            ->ban($user, 'phpspec', false)
            ->shouldReturn(true);
    }
}
