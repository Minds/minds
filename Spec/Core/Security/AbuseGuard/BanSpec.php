<?php

namespace Spec\Minds\Core\Security\AbuseGuard;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Sessions;
use Minds\Entities\User;
use Minds\Core\Security\AbuseGuard\AccusedEntity;

class BanSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\AbuseGuard\Ban');
    }

    function it_should_set_accused(AccusedEntity $accused)
    {
        $this->setAccused($accused)->shouldReturn($this);
    }

    function it_should_ban_a_user(AccusedEntity $accused, User $user, Sessions $sessions)
    {
        $this->beConstructedWith($sessions);

        $user->get('guid')->willReturn(123);
        $user->set('ban_reason', 'spam')->shouldBeCalled();
        $user->set('banned', 'yes')->shouldBeCalled();
        $user->set('code', '')->shouldBeCalled();
        $user->save()->willReturn(true);

        $accused->getUser()->willReturn($user);
        $this->setAccused($accused);

        $this->ban()->shouldBe(true);

        $sessions->destroyAll(123)->shouldBeCalled();
    }
}
