<?php

namespace Spec\Minds\Core\Channels\Delegates;

use Minds\Core\Channels\Delegates\Unban;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UnbanSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Unban::class);
    }

    function it_should_ban(User $user)
    {
        $user->set('ban_reason', '')
            ->shouldBeCalled();

        $user->set('banned', 'no')
            ->shouldBeCalled();

        $user->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->unban($user, false)
            ->shouldReturn(true);
    }
}
