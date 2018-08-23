<?php

namespace Spec\Minds\Core\Channels\Delegates;

use Minds\Core\Channels\Delegates\Logout;
use Minds\Core\Data\Sessions;
use Minds\Entities\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LogoutSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Logout::class);
    }

    function it_should_logout(Sessions $sessions)
    {
        $this->beConstructedWith($sessions);
        $user = new User();
        $user->guid = 123;

        $sessions->destroyAll(123)
            ->shouldBeCalled();

        $this->logout($user);
    }

}
