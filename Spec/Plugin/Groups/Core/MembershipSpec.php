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

    function it_should_get_members(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('member', Argument::any())->shouldBeCalled()->willReturn([1, 2]);

        $group->getGuid()->willReturn(50);

        $this->getMembers([ 'hydrate' => false ])->shouldReturn([1, 2]);
    }

    function it_should_get_members_count(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $db->setGuid(50)->shouldBeCalled();
        $db->countInverse('member')->shouldBeCalled()->willReturn(2);

        $group->getGuid()->willReturn(50);

        $this->getMembersCount()->shouldReturn(2);
    }

    function it_should_get_requests(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('membership_request', Argument::any())->shouldBeCalled()->willReturn([3, 4, 5]);

        $group->getGuid()->willReturn(50);

        $this->getRequests([ 'hydrate' => false ])->shouldReturn([3, 4, 5]);
    }

    function it_should_get_requests_count(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $db->setGuid(50)->shouldBeCalled();
        $db->countInverse('membership_request')->shouldBeCalled()->willReturn(3);

        $group->getGuid()->willReturn(50);

        $this->getRequestsCount()->shouldReturn(3);
    }

    function it_should_join(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('membership_request', 50)->shouldBeCalled();
        $db->create('member', 50)->shouldBeCalled()->willReturn(true);

        $this->join($user)->shouldReturn(true);
    }

    function it_should_request_to_join(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(false);
        $group->canEdit($user)->shouldBeCalled()->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('membership_request', 50)->shouldBeCalled()->willReturn(true);

        $this->join($user)->shouldReturn(true);
    }

    function it_should_forcefully_join_an_admin(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(false);
        $group->canEdit($user)->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('membership_request', 50)->shouldBeCalled();
        $db->create('member', 50)->shouldBeCalled()->willReturn(true);

        $this->join($user)->shouldReturn(true);
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
