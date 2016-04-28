<?php

namespace Messenger\Spec\Minds\Plugin\Messenger\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;

class ConversationSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Plugin\Messenger\Core\Conversation');
    }

    function it_should_permutate_participants(User $user1, User $user2)
    {
        $user1->get('guid')->shouldBeCalled()->willReturn('abc');
        $user2->get('guid')->shouldBeCalled()->willReturn('123');
        $this->setParticipant($user1)
          ->setParticipant($user2);
        $this->getIndexKey()->shouldReturn('abc:123');
    }

    function it_should_permutate_participants_in_order(User $user1, User $user2)
    {
        $user1->get('guid')->shouldBeCalled()->willReturn('456');
        $user2->get('guid')->shouldBeCalled()->willReturn('123');
        $this->setParticipant($user1)
          ->setParticipant($user2);
        $this->getIndexKey()->shouldReturn('123:456');
    }

    function it_should_permutate_group_participants_in_order(User $user1, User $user2, User $user3, User $user4)
    {
        $user1->get('guid')->shouldBeCalled()->willReturn('100000000000000063');
        $user2->get('guid')->shouldBeCalled()->willReturn('100000000000000003');
        $user3->get('guid')->shouldBeCalled()->willReturn('100000000000000599');
        $user4->get('guid')->shouldBeCalled()->willReturn('245660000000000063');
        $this->setParticipant($user1)
          ->setParticipant($user2)
          ->setParticipant($user3)
          ->setParticipant($user4);
        $this->getIndexKey()->shouldReturn('100000000000000003:100000000000000063:100000000000000599:245660000000000063');
    }

}
