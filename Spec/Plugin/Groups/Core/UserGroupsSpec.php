<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Relationships;

class UserGroupsSpec extends ObjectBehavior
{
    function it_is_initializable(User $user, Relationships $db)
    {
        $this->beConstructedWith($user, $db);
        $this->shouldHaveType('Minds\Plugin\Groups\Core\UserGroups');
    }

    function it_should_get_invitations(User $user, Relationships $db)
    {
        $this->beConstructedWith($user, $db);

        $db->setGuid(1)->shouldBeCalled();
        $db->get('member', Argument::any())->shouldBeCalled()->willReturn([50, 51]);

        $user->get('guid')->willReturn(1);

        $this->getGroups([ 'hydrate' => false ])->shouldReturn([50, 51]);
    }
}
