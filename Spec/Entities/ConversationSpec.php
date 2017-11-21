<?php

namespace Spec\Minds\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Indexes;

class ConversationSpec extends ObjectBehavior
{
    public function it_is_initializable(User $user1)
    {
        $this->shouldHaveType('Minds\Entities\Conversation');
    }

    public function it_should_permutate_participants(User $user1, User $user2)
    {
        $user1->get('guid')->shouldBeCalled()->willReturn('456');
        $user2->get('guid')->shouldBeCalled()->willReturn('123');
        $this->setParticipant($user1)
          ->setParticipant($user2);
        $this->getGuid()->shouldReturn('123:456');
    }

    public function it_should_permutate_participants_in_order(User $user1, User $user2)
    {
        $user1->get('guid')->shouldBeCalled()->willReturn('456');
        $user2->get('guid')->shouldBeCalled()->willReturn('123');
        $this->setParticipant($user1)
          ->setParticipant($user2);
        $this->getGuid()->shouldReturn('123:456');
    }

    public function it_should_permutate_group_participants_in_order(User $user1, User $user2, User $user3, User $user4)
    {
        $user1->get('guid')->shouldBeCalled()->willReturn('100000000000000063');
        $user2->get('guid')->shouldBeCalled()->willReturn('100000000000000003');
        $user3->get('guid')->shouldBeCalled()->willReturn('100000000000000599');
        $user4->get('guid')->shouldBeCalled()->willReturn('245660000000000063');
        $this->setParticipant($user1)
          ->setParticipant($user2)
          ->setParticipant($user3)
          ->setParticipant($user4);
        $this->getGuid()->shouldReturn('100000000000000003:100000000000000063:100000000000000599:245660000000000063');
    }

}
