<?php

namespace Spec\Minds\Core\Onboarding\Delegates;

use Minds\Core\Onboarding\Delegates\AvatarDelegate;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AvatarDelegateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AvatarDelegate::class);
    }

    function it_should_check_if_completed(User $user) {
        /*$user->get('time_created')
            ->shouldBeCalled()
            ->willReturn(500000);

        $user->getLastAvatarUpload()
            ->shouldBeCalled()
            ->willReturn(500001);*/

        $this
            ->isCompleted($user)
            ->shouldReturn(true);
    }

    /*function it_should_check_if_not_completed(User $user) {
        $user->get('time_created')
            ->shouldBeCalled()
            ->willReturn(500000);

        $user->getLastAvatarUpload()
            ->shouldBeCalled()
            ->willReturn(500000);

        $this
            ->isCompleted($user)
            ->shouldReturn(false);
    }*/

    /*function it_should_assume_that_legacy_users_have_avatars(User $user) {
        $user->get('time_created')
            ->shouldBeCalled()
            ->willReturn(500000);

        $user->getLastAvatarUpload()
            ->shouldBeCalled()
            ->willReturn(0);

        $this
            ->isCompleted($user)
            ->shouldReturn(true);
    }*/
}
