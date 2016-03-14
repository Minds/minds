<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Security\ACL;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Plugin\Groups\Core\Featured;
use Minds\Plugin\Groups\Core\Activity;
use Minds\Plugin\Groups\Core\Membership;
use Minds\Plugin\Groups\Core\Notifications;
use Minds\Plugin\Groups\Core\Invitations;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class GroupSpec extends ObjectBehavior
{
    function it_is_initializable(Relationships $db, GroupEntity $group)
    {
        $this->beConstructedWith($group, $db);
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Group');
    }

    function it_should_export(
        Relationships $db,
        GroupEntity $group,
        Featured $featured,
        ACL $acl,
        Activity $activity,
        Membership $membership,
        Notifications $notifications,
        Invitations $invitations,
        User $user
    )
    {
        $this->beConstructedWith($group, $db, $featured, $acl, $activity, $membership, $notifications, $invitations);

        $group->export()->shouldBeCalled()->willReturn([ 'guid' => 50 ]);
        $group->isMember($user)->shouldBeCalled()->willReturn(true);
        $group->isCreator($user)->shouldBeCalled()->willReturn(true);
        $group->isOwner($user)->shouldBeCalled()->willReturn(true);

        $acl->read($group, $user)->willReturn(true);

        $activity->count()->shouldBeCalled()->willReturn(1000);
        $membership->isAwaiting($user)->shouldBeCalled()->willReturn(false);
        $membership->isBanned($user)->shouldBeCalled()->willReturn(false);
        $membership->getMembers()->shouldBeCalled()->willReturn([ ]);
        $membership->getMembersCount()->shouldBeCalled()->willReturn(5);
        $membership->getRequestsCount()->shouldBeCalled()->willReturn(3);
        $notifications->isMuted($user)->shouldBeCalled()->willReturn(false);
        $invitations->isInvited($user)->shouldBeCalled()->willReturn(false);

        $this->setActor($user);

        $this->export()->shouldReturn([
            'guid' => 50,
            'activity:count' => 1000,
            'is:invited' => false,
            'is:awaiting' => false,
            'is:banned' => false,
            'members' => [],
            'members:count' => 5,
            'is:member' => true,
            'is:muted' => false,
            'is:creator' => true,
            'is:owner' => true,
            'requests:count' => 3,
        ]);
    }

    function it_should_feature(Relationships $db, GroupEntity $group, Featured $featured)
    {
        $this->beConstructedWith($group, $db, $featured);

        $group->setFeatured(1)->shouldBeCalled();
        $group->getFeaturedId()->shouldBeCalled()->willReturn(1050);
        $group->save()->shouldBeCalled()->willReturn(true);

        $featured->feature($group)->shouldBeCalled();

        $this->feature()->shouldReturn(1050);
    }

    function it_should_unfeature(Relationships $db, GroupEntity $group, Featured $featured)
    {
        $this->beConstructedWith($group, $db, $featured);

        $group->setFeatured(0)->shouldBeCalled();
        $group->setFeaturedId(null)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $featured->unfeature($group)->shouldBeCalled();

        $this->unfeature()->shouldReturn(true);
    }

    function it_should_delete(Relationships $db, GroupEntity $group, Featured $featured, User $user)
    {
        $this->beConstructedWith($group, $db, $featured);

        $group->delete()->shouldBeCalled()->willReturn(true);
        $group->isOwner($user)->shouldBeCalled()->willReturn(true);

        $featured->unfeature($group)->shouldBeCalled();

        $this->setActor($user);

        $this->delete([ 'cleanup' => false ])->shouldReturn(true);
    }

    function it_should_not_delete(Relationships $db, GroupEntity $group, User $user)
    {
        $this->beConstructedWith($group, $db, null);

        $group->isOwner($user)->shouldBeCalled()->willReturn(false);

        $this->setActor($user);

        $this->shouldThrow('\Minds\Plugin\Groups\Exceptions\GroupOperationException')->duringDelete();
    }
}
