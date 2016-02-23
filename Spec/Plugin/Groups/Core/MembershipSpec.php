<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class MembershipSpec extends ObjectBehavior
{

    function it_is_initializable(GroupEntity $entity, Relationships $db)
    {
        $this->beConstructedWith($entity, $db);
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Membership');
    }

    function it_should_ban_a_user(User $user, User $actor, GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('member', Argument::any())->willReturn(false);
        $db->create('group:banned', 50)->willReturn(true);

        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);

        $group->getGuid()->willReturn(50);

        $this->ban($user, $actor)->shouldReturn(true);
    }

}
