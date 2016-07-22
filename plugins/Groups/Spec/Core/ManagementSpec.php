<?php

namespace Groups\Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Security\ACL;
use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class ManagementSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Management');
    }

    public function it_should_grant(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $actor)
    {
        $this->beConstructedWith($db, $acl);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);

        $group->getGuid()->willReturn(50);
        $group->isMember($user)->shouldBeCalled()->willReturn(true);
        $group->pushOwnerGuid(1)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:owner', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->grant($user)->shouldReturn(true);
    }

    public function it_should_revoke(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $actor)
    {
        $this->beConstructedWith($db, $acl);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);

        $group->getGuid()->willReturn(50);
        $group->removeOwnerGuid(1)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:owner', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->revoke($user)->shouldReturn(true);
    }
}
