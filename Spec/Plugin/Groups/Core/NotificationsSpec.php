<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class NotificationsSpec extends ObjectBehavior
{
    function it_is_initializable(GroupEntity $entity, Relationships $db)
    {
        $this->beConstructedWith($entity, $db);
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Notifications');
    }

    // TODO: [emi] queue() and send()

    function it_should_get_recipients(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('member', Argument::any())->shouldBeCalled()->willReturn([1, 2, 3, 4, 5, 6, 7, 8]);
        $db->get('group:muted', Argument::any())->shouldBeCalled()->willReturn([6, 7]);

        $group->getGuid()->willReturn(50);

        $this->getRecipients([ 'exclude' => [ 1 ] ])->shouldReturn(['2', '3', '4', '5', '8']);
    }

    function it_should_get_muted_members(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('group:muted', Argument::any())->shouldBeCalled()->willReturn([6, 7]);

        $group->getGuid()->willReturn(50);

        $this->getMutedMembers()->shouldReturn([6, 7]);
    }

    function it_should_check_muted_members_in_batch(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($group, $db);

        $group->getGuid()->willReturn(50);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('group:muted', Argument::any())->shouldBeCalled()->willReturn([11, 12, 13]);

        $this->isMutedBatch([11, 12, 14])->shouldReturn([11 => true, 12 => true, 14 => false]);
    }

    function it_should_check_if_its_muted(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:muted', 50)->shouldBeCalled()->willReturn(true);

        $this->isMuted($user)->shouldReturn(true);
    }

    function it_should_mute(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:muted', 50)->shouldBeCalled()->willReturn(true);

        $this->mute($user)->shouldReturn(true);
    }

    function it_should_unmute(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($group, $db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:muted', 50)->shouldBeCalled()->willReturn(true);

        $this->unmute($user)->shouldReturn(true);
    }
}
