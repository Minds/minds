<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Security\ACL;
use Minds\Core\Data\Call;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class InvitationsSpec extends ObjectBehavior
{
    function it_is_initializable(GroupEntity $entity, Relationships $db, ACL $acl)
    {
        $this->beConstructedWith($entity, $db, $acl);
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Invitations');
    }

    function it_should_get_invitations(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('group:invited', Argument::any())->shouldBeCalled()->willReturn([11, 12, 13]);

        $group->getGuid()->willReturn(50);

        $this->getInvitations([ 'hydrate' => false ])->shouldReturn([11, 12, 13]);
    }

    function it_should_check_invited_users_in_batch(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $group->getGuid()->willReturn(50);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('group:invited', Argument::any())->shouldBeCalled()->willReturn([11, 12, 13]);

        $this->isInvitedBatch([11, 12, 14])->shouldReturn([11 => true, 12 => true, 14 => false]);
    }

    function it_should_check_if_its_invited(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $this->isInvited($user)->shouldReturn(true);
    }

    function it_should_invite_to_a_public_group(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($group, $db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(true);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $friendsDB->getRow(2, Argument::any())->shouldBeCalled()->willReturn([ '1' => 123456 ]);

        $this->setActor($actor);
        $this->invite($user, [ 'notify' => false ])->shouldReturn(true);
    }

    function it_should_not_invite_to_a_private_group(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($group, $db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(false);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->create('group:invited', 50)->shouldNotBeCalled();

        $acl->write($group, $actor)->shouldBeCalled()->willReturn(false);

        $this->setActor($actor);
        $this->shouldThrow('\Minds\Plugin\Groups\Exceptions\GroupOperationException')->duringInvite($user, [ 'notify' => false ]);
    }

    function it_should_invite_to_a_private_group_by_an_owner(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($group, $db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(false);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $friendsDB->getRow(2, Argument::any())->shouldBeCalled()->willReturn([ '1' => 123456 ]);

        $this->setActor($actor);
        $this->invite($user, [ 'notify' => false ])->shouldReturn(true);
    }

    function it_should_uninvite(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($group, $db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(true);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $friendsDB->getRow(2, Argument::any())->shouldBeCalled()->willReturn([ '1' => 123456 ]);

        $this->setActor($actor);
        $this->uninvite($user)->shouldReturn(true);
    }

    function it_should_not_uninvite_a_non_subscriber(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($group, $db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(true);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->remove('group:invited', 50)->shouldNotBeCalled();

        $friendsDB->getRow(2, Argument::any())->shouldBeCalled()->willReturn([]);

        $this->setActor($actor);
        $this->shouldThrow('\Minds\Plugin\Groups\Exceptions\GroupOperationException')->duringUninvite($user);
    }

    function it_should_accept(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:invited', 50)->shouldBeCalled()->willReturn(true);
        $db->remove('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $group->join($user, [ 'force' => true ])->shouldBeCalled()->willReturn(true);

        $this->setActor($user);
        $this->accept()->shouldReturn(true);
    }

    function it_should_fail_to_accept_if_not_invited(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:invited', 50)->shouldBeCalled()->willReturn(false);
        $db->remove('group:invited', 50)->shouldNotBeCalled();

        $group->join($user, [ 'force' => true ])->shouldNotBeCalled();

        $this->setActor($user);
        $this->shouldThrow('\Minds\Plugin\Groups\Exceptions\GroupOperationException')->duringAccept();
    }

    function it_should_decline(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:invited', 50)->shouldBeCalled()->willReturn(true);
        $db->remove('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $this->setActor($user);
        $this->decline()->shouldReturn(true);
    }
}
