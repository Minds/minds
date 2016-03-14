<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Security\ACL;
use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class ManagementSpec extends ObjectBehavior
{
    function it_is_initializable(GroupEntity $entity, Relationships $db, ACL $acl)
    {
        $this->beConstructedWith($entity, $db, $acl);
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Management');
    }

    function it_should_grant(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $actor)
    {
        $this->beConstructedWith($group, $db, $acl);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);

        $group->getGuid()->willReturn(50);
        $group->isMember($user)->shouldBeCalled()->willReturn(true);
        $group->pushOwnerGuid(1)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:owner', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setActor($actor);
        $this->grant($user)->shouldReturn(true);
    }

    function it_should_revoke(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $actor)
    {
        $this->beConstructedWith($group, $db, $acl);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);

        $group->getGuid()->willReturn(50);
        $group->removeOwnerGuid(1)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:owner', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setActor($actor);
        $this->revoke($user)->shouldReturn(true);
    }

    function it_should_check_if_creator(GroupEntity $group, Relationships $db, ACL $acl, User $user)
    {
        $this->beConstructedWith($group, $db, $acl);

        $user->get('guid')->willReturn(1);

        $group->getOwnerObj()->shouldBeCalled()->willReturn($user);

        $this->isCreator($user)->shouldReturn(true);
    }

    function it_should_check_if_not_a_creator(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $creator)
    {
        $this->beConstructedWith($group, $db, $acl);

        $user->get('guid')->willReturn(1);

        $creator->get('guid')->willReturn(2);

        $group->getOwnerObj()->shouldBeCalled()->willReturn($creator);

        $this->isCreator($user)->shouldReturn(false);
    }

    function it_should_check_if_creator_is_owner(GroupEntity $group, Relationships $db, ACL $acl, User $creator)
    {
        $this->beConstructedWith($group, $db, $acl);

        $creator->get('guid')->willReturn(2);

        $group->getOwnerObj()->shouldBeCalled()->willReturn($creator);
        $group->getOwnerGuids()->shouldNotBeCalled();

        $this->isOwner($creator)->shouldReturn(true);
    }

    function it_should_check_if_admin_is_owner(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $creator)
    {
        $this->beConstructedWith($group, $db, $acl);

        $user->get('guid')->willReturn(1);

        $creator->get('guid')->willReturn(2);

        $group->getOwnerObj()->shouldBeCalled()->willReturn($creator);
        $group->getOwnerGuids()->shouldBeCalled()->willReturn([1, 3]);

        $this->isOwner($user)->shouldReturn(true);
    }

    function it_should_check_if_not_an_owner(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $creator)
    {
        $this->beConstructedWith($group, $db, $acl);

        $user->get('guid')->willReturn(5);

        $creator->get('guid')->willReturn(2);

        $group->getOwnerObj()->shouldBeCalled()->willReturn($creator);
        $group->getOwnerGuids()->shouldBeCalled()->willReturn([1, 3]);

        $this->isOwner($user)->shouldReturn(false);
    }
}
