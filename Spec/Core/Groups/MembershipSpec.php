<?php

namespace Spec\Minds\Core\Groups;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Entities\Group as GroupEntity;
use Minds\Core\Security\ACL;

class MembershipSpec extends ObjectBehavior
{

    private $_db;
    private $_acl;
    private $_notifications;

    function let(
        Relationships $db,
        ACL $acl,
        \Minds\Core\Groups\Notifications $notifications
    )
    {
        $this->beConstructedWith($db, $notifications, $acl);
        $this->_db = $db;
        $this->_acl = $acl;
        $this->_notifications = $notifications;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Groups\Membership');
    }

    public function it_should_get_members(GroupEntity $group, Relationships $db)
    {
        $this->_db->setGuid(50)->shouldBeCalled();
        $this->_db->get('member', Argument::any())->shouldBeCalled()->willReturn([1, 2]);

        $group->getGuid()->willReturn(50);

        $this->setGroup($group);
        $this->getMembers([ 'hydrate' => false ])->shouldReturn([1, 2]);
    }

    public function it_should_get_members_count(GroupEntity $group, Relationships $db)
    {
        $this->_db->setGuid(50)->shouldBeCalled();
        $this->_db->countInverse('member')->shouldBeCalled()->willReturn(2);

        $group->getGuid()->willReturn(50);

        $this->setGroup($group);
        $this->getMembersCount()->shouldReturn(2);
    }

    public function it_should_get_requests(GroupEntity $group, Relationships $db)
    {
        $this->_db->setGuid(50)->shouldBeCalled();
        $this->_db->get('membership_request', Argument::any())->shouldBeCalled()->willReturn([3, 4, 5]);

        $group->getGuid()->willReturn(50);

        $this->setGroup($group);
        $this->getRequests([ 'hydrate' => false ])->shouldReturn([3, 4, 5]);
    }

    public function it_should_get_requests_count(GroupEntity $group, Relationships $db)
    {
        $this->_db->setGuid(50)->shouldBeCalled();
        $this->_db->countInverse('membership_request')->shouldBeCalled()->willReturn(3);

        $group->getGuid()->willReturn(50);

        $this->setGroup($group);
        $this->getRequestsCount()->shouldReturn(3);
    }

    public function it_should_join_a_public_group(GroupEntity $group, Relationships $db, User $user)
    {
        $user->get('guid')->willReturn(1);
        $user->getGroupMembership()->willReturn([]);
        $user->context(Argument::type('string'))->willReturn();
        $user->setGroupMembership(Argument::any())->willReturn($user);
        $user->save()->willReturn(true);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(true);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('membership_request', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->check('member', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->check('group:banned', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->remove('membership_request', 50)->shouldNotBeCalled();
        $this->_db->create('member', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($user);
        $this->join($user)->shouldReturn(true);
    }

    public function it_should_not_join_a_public_group_if_banned(GroupEntity $group, Relationships $db, User $user)
    {
        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(true);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('member', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->check('group:banned', 50)->shouldBeCalled()->willReturn(true);
        $this->_db->remove('membership_request', 50)->shouldNotBeCalled();
        $this->_db->create('member', 50)->shouldNotBeCalled();

        $this->setGroup($group);
        $this->setActor($user);
        $this->shouldThrow('\Minds\Exceptions\GroupOperationException')->duringJoin($user);
    }

    public function it_should_request_to_join_a_closed_group(GroupEntity $group, Relationships $db, User $user, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $user->getGroupMembership()->willReturn([]);
        $user->context(Argument::type('string'))->willReturn();
        $user->setGroupMembership(Argument::any())->willReturn($user);
        $user->save()->willReturn(true);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(false);
        $group->isInvited(Argument::any())->shouldBeCalled()->willReturn(false);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('member', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->check('group:banned', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->check('membership_request', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->create('membership_request', 50)->shouldBeCalled()->willReturn(true);

        $this->_acl->write($group, $user)->shouldBeCalled()->willReturn(false);

        $this->setGroup($group);
        $this->setActor($user);
        $this->join($user)->shouldReturn(true);
    }

    public function it_should_forcefully_join_an_admin(GroupEntity $group, Relationships $db, User $user, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $user->getGroupMembership()->willReturn([]);
        $user->context(Argument::type('string'))->willReturn();
        $user->setGroupMembership(Argument::any())->willReturn($user);
        $user->save()->willReturn(true);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->shouldBeCalled()->willReturn(false);
        $group->isInvited(Argument::any())->shouldBeCalled()->willReturn(false);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('membership_request', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->check('member', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->check('group:banned', 50)->shouldBeCalled()->willReturn(false);
        $this->_db->create('member', 50)->shouldBeCalled()->willReturn(true);

        $this->_acl->write($group, $user)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($user);
        $this->join($user)->shouldReturn(true);
    }

    public function it_should_leave(GroupEntity $group, Relationships $db, User $user, \Minds\Core\Groups\Notifications $notifications)
    {
        $user->get('guid')->willReturn(1);
        $user->getGroupMembership()->willReturn([]);
        $user->context(Argument::type('string'))->willReturn();
        $user->setGroupMembership(Argument::any())->willReturn($user);
        $user->save()->willReturn(true);

        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('member', 50)->shouldBeCalled()->willReturn(true);
        $this->_db->remove('member', 50)->shouldBeCalled()->willReturn(true);

        $this->_notifications->setGroup($group)->shouldBeCalled();
        $this->_notifications->unmute(1)->shouldBeCalled()->willReturn(null);

        $this->setGroup($group);
        $this->leave($user)->shouldReturn(true);
    }

    public function it_should_kick(GroupEntity $group, Relationships $db, User $user, User $actor, \Minds\Core\Groups\Notifications $notifications, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $user->getGroupMembership()->willReturn([]);
        $user->context(Argument::type('string'))->willReturn();
        $user->setGroupMembership(Argument::any())->willReturn($user);
        $user->save()->willReturn(true);

        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('member', 50)->shouldBeCalled()->willReturn(true);
        $this->_db->remove('member', 50)->shouldBeCalled()->willReturn(true);

        $this->_notifications->setGroup($group)->shouldBeCalled();
        $this->_notifications->unmute(1)->shouldBeCalled()->willReturn(null);
        //$this->_notifications->sendKickNotification(1)->shouldBeCalled()->willReturn(null);

        //$this->_acl->write($group, $user)->shouldBeCalled()->willReturn(false);
        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->kick($user)->shouldReturn(true);
    }

    public function it_should_not_kick_if_not_an_owner(GroupEntity $group, Relationships $db, User $user, User $actor,  \Minds\Core\Groups\Notifications $notifications, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);

        $this->_db->remove('member', 50)->shouldNotBeCalled();

        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(false);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->shouldThrow('\Minds\Exceptions\GroupOperationException')->duringKick($user);
    }

    /*function it_should_not_kick_an_owner(GroupEntity $group, Relationships $db, User $user, User $actor, Notifications $notifications, ACL $acl)
    {
        $this->beConstructedWith($db, $notifications, $acl);

        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);

        $this->_db->remove('member', 50)->shouldNotBeCalled();

        $this->_acl->write($group, $user)->shouldBeCalled()->willReturn(true);
        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->shouldThrow('\Minds\Exceptions\GroupOperationException')->duringKick($user);
    }*/

    public function it_should_check_if_its_a_member(GroupEntity $group, Relationships $db, User $user)
    {
        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('member', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->isMember($user)->shouldReturn(true);
    }

    public function it_should_ban(GroupEntity $group, Relationships $db, User $user, User $actor, \Minds\Core\Groups\Notifications $notifications, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $user->getGroupMembership()->willReturn([]);
        $user->context(Argument::type('string'))->willReturn();
        $user->setGroupMembership(Argument::any())->willReturn($user);
        $user->save()->willReturn(true);

        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);
        //$group->getOwnerObj()->willReturn($actor);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('member', 50)->shouldBeCalled()->willReturn(true);
        $this->_db->remove('member', 50)->shouldBeCalled()->willReturn(true);
        $this->_db->create('group:banned', 50)->shouldBeCalled()->willReturn(true);

        $this->_notifications->setGroup($group)->shouldBeCalled();
        $this->_notifications->unmute(1)->shouldBeCalled()->willReturn(null);

        //$this->_acl->write($group, $user)->shouldBeCalled()->willReturn(false);
        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->ban($user)->shouldReturn(true);
    }

    public function it_should_not_ban_if_not_an_owner(GroupEntity $group, Relationships $db, User $user, User $actor, \Minds\Core\Groups\Notifications $notifications, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);
        //$group->getOwnerObj()->willReturn($actor);

        $this->_db->remove('member', 50)->shouldNotBeCalled();
        $this->_db->create('group:banned', 50)->shouldNotBeCalled();

        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(false);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->shouldThrow('\Minds\Exceptions\GroupOperationException')->duringBan($user);
    }

    public function it_should_not_ban_an_owner(GroupEntity $group, Relationships $db, User $user, User $actor, \Minds\Core\Groups\Notifications $notifications, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);
        //$group->getOwnerObj()->willReturn($actor);

        //$this->_db->remove('member', 50)->shouldNotBeCalled();
        //$this->_db->create('group:banned', 50)->shouldNotBeCalled();

        //$this->_acl->write($group, $user)->shouldBeCalled()->willReturn(true);
        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->shouldThrow('\Minds\Exceptions\GroupOperationException')->duringBan($user);
    }

    public function it_should_unban(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);
        //$group->getOwnerObj()->willReturn($actor);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->remove('group:banned', 50)->shouldBeCalled()->willReturn(true);

        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->unban($user)->shouldReturn(true);
    }

    public function it_should_not_unban_if_not_an_owner(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);
        //$group->getOwnerObj()->willReturn($actor);

        $this->_db->remove('group:banned', 50)->shouldNotBeCalled();

        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(false);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->shouldThrow('\Minds\Exceptions\GroupOperationException')->duringUnban($user);
    }

    public function it_should_check_if_its_banned(GroupEntity $group, Relationships $db, User $user)
    {
        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('group:banned', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->isBanned($user)->shouldReturn(true);
    }

    public function it_should_check_banned_users(GroupEntity $group, Relationships $db)
    {
        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(50)->shouldBeCalled();
        $this->_db->get('group:banned', Argument::any())->shouldBeCalled()->willReturn([3, 4, 5]);

        $this->setGroup($group);
        $this->getBannedUsers([ 'hydrate' => false ])->shouldReturn([3, 4, 5]);
    }

    public function it_should_check_banned_users_in_batch(GroupEntity $group, Relationships $db)
    {
        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(50)->shouldBeCalled();
        $this->_db->get('group:banned', Argument::any())->shouldBeCalled()->willReturn([3, 4, 5]);

        $this->setGroup($group);
        $this->isBannedBatch([3, 4, 6])->shouldReturn([3 => true, 4 => true, 6 => false]);
    }

    public function it_should_check_request(GroupEntity $group, Relationships $db, User $user)
    {
        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->check('membership_request', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->isAwaiting($user)->shouldReturn(true);
    }

    public function it_should_cancel_request(GroupEntity $group, Relationships $db, User $user, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->remove('membership_request', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($user);
        $this->cancelRequest($user)->shouldReturn(true);
    }

    public function it_should_cancel_request_if_owner(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);

        $this->_db->setGuid(1)->shouldBeCalled();
        $this->_db->remove('membership_request', 50)->shouldBeCalled()->willReturn(true);

        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(true);
        //$this->_acl->write($group, $user)->shouldBeCalled()->willReturn(false);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->cancelRequest($user)->shouldReturn(true);
    }

    public function it_should_not_cancel_request_if_not_an_owner(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl)
    {
        $user->get('guid')->willReturn(1);
        $actor->get('guid')->willReturn(2);
        $group->getGuid()->willReturn(50);

        $this->_db->remove('membership_request', 50)->shouldNotBeCalled();

        $this->_acl->write($group, $actor)->shouldBeCalled()->willReturn(false);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->shouldThrow('\Minds\Exceptions\GroupOperationException')->duringCancelRequest($user);
    }

    /*public function it_should_accept_all_requests(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($db);

        $this->_db->setGuid(50)->shouldBeCalled();
        $this->_db->get('membership_request', [ 'limit' => 500, 'offset' => '', 'inverse' => true ])->shouldBeCalled()->willReturn([3, 4, 5]);
        $this->_db->get('membership_request', [ 'limit' => 500, 'offset' => 5, 'inverse' => true ])->shouldBeCalled()->willReturn([6, 7]);
        $this->_db->get('membership_request', [ 'limit' => 500, 'offset' => 7, 'inverse' => true ])->shouldBeCalled()->willReturn([]);
        $this->_db->setGuid(3)->shouldBeCalled();
        $this->_db->setGuid(4)->shouldBeCalled();
        $this->_db->setGuid(5)->shouldBeCalled();
        $this->_db->setGuid(6)->shouldBeCalled();
        $this->_db->setGuid(7)->shouldBeCalled();
        $this->_db->create('member', 50)->shouldBeCalled()->willReturn(true);
        $this->_db->remove('membership_request', 50)->shouldBeCalled()->willReturn(true);

        $group->getGuid()->willReturn(50);

        $this->setGroup($group);
        $this->acceptAllRequests();
    }*/
}
