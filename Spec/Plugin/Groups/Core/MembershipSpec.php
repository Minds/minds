<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Plugin\Groups\Core\Notifications;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Core\Security\ACL;

class MembershipSpec extends ObjectBehavior
{
    function it_is_initializable(GroupEntity $entity, Relationships $db, Notifications $notifications, ACL $acl)
    {
        $this->beConstructedWith($entity, $db, $notifications, $acl);
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

    function it_should_request_to_join(GroupEntity $group, Relationships $db, User $user, ACL $acl)
    {
        $this->beConstructedWith($group, $db, null, $acl);

        $user->get('guid')->willReturn(1);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('membership_request', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $user)->shouldBeCalled()->willReturn(false);

        $this->join($user)->shouldReturn(true);
    }

    function it_should_forcefully_join_an_admin(GroupEntity $group, Relationships $db, User $user, ACL $acl)
    {
        $this->beConstructedWith($group, $db, null, $acl);

        $user->get('guid')->willReturn(1);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('membership_request', 50)->shouldBeCalled();
        $db->create('member', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $user)->shouldBeCalled()->willReturn(true);

        $this->join($user)->shouldReturn(true);
    }

    function it_should_leave(GroupEntity $group, Relationships $db, User $user, Notifications $notifications)
    {
        $this->beConstructedWith($group, $db, $notifications);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('member', 50)->shouldBeCalled()->willReturn(true);

        $notifications->unmute(1)->shouldBeCalled()->willReturn(null);

        $this->leave($user)->shouldReturn(true);
    }

    function it_should_kick(GroupEntity $group, Relationships $db, User $user, Notifications $notifications)
    {
        $this->beConstructedWith($group, $db, $notifications);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('member', 50)->shouldBeCalled()->willReturn(true);

        $notifications->unmute(1)->shouldBeCalled()->willReturn(null);
        $notifications->sendKickNotification(1)->shouldBeCalled()->willReturn(null);

        $this->kick($user)->shouldReturn(true);
    }

    function it_should_check_if_its_a_member(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('member', 50)->shouldBeCalled()->willReturn(true);

        $this->isMember($user)->shouldReturn(true);
    }

    function it_should_ban(GroupEntity $group, Relationships $db, User $user, User $actor, Notifications $notifications)
    {
        $this->beConstructedWith($group, $db, $notifications);

        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);
        $group->getOwnerObj()->willReturn($actor);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('member', 50)->shouldBeCalled()->willReturn(true);
        $db->remove('member', 50)->shouldBeCalled()->willReturn(true);
        $db->create('group:banned', 50)->shouldBeCalled()->willReturn(true);

        $notifications->unmute(1)->shouldBeCalled()->willReturn(null);
        $notifications->sendKickNotification(1)->shouldBeCalled()->willReturn(null);

        $this->ban($user, $actor)->shouldReturn(true);
    }

    function it_should_unban(GroupEntity $group, Relationships $db, User $user, User $actor)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);
        $group->getOwnerObj()->willReturn($actor);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:banned', 50)->shouldBeCalled()->willReturn(true);

        $this->unban($user, $actor)->shouldReturn(true);
    }

    function it_should_check_if_its_banned(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:banned', 50)->shouldBeCalled()->willReturn(true);

        $this->isBanned($user)->shouldReturn(true);
    }

    function it_should_check_banned_users(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $group->getGuid()->willReturn(50);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('group:banned', Argument::any())->shouldBeCalled()->willReturn([3, 4, 5]);

        $this->getBannedUsers([ 'hydrate' => false ])->shouldReturn([3, 4, 5]);
    }

    function it_should_check_banned_users_in_batch(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $group->getGuid()->willReturn(50);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('group:banned', Argument::any())->shouldBeCalled()->willReturn([3, 4, 5]);

        $this->isBannedBatch([3, 4, 6])->shouldReturn([3 => true, 4 => true, 6 => false]);
    }

    function it_should_cancel_request(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('membership_request', 50)->shouldBeCalled()->willReturn(true);

        $this->cancelRequest($user)->shouldReturn(true);
    }
}
