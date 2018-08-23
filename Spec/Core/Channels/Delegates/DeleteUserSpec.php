<?php

namespace Spec\Minds\Core\Channels\Delegates;

use Minds\Core\Channels\Delegates\DeleteUser;
use Minds\Entities\User;
use Minds\Core\Data\Call;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteUserSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(DeleteUser::class);
    }

    function it_should_delete_a_user(
        Call $indexes,
        Call $entities,
        Call $lookup
    )
    {
        $this->beConstructedWith($indexes, $entities, $lookup);

        $user = new User;
        $user->guid = 123;
        $user->username = 'markna';

        $entities->removeRow(123)
            ->shouldBeCalled();

        $indexes->removeAttributes('user', [ 123 ])
            ->shouldBeCalled();

        $lookup->removeRow('markna')
            ->shouldBeCalled();

        $this->delete($user);
    }

}
