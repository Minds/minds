<?php

namespace Messenger\Spec\Minds\Plugin\Messenger\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Core\Data\Cassandra\Thrift\Indexes;

class ConversationSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Plugin\Messenger\Core\Conversation');
    }

    public function it_should_permutate_participants(User $user1, User $user2)
    {
        $user1->get('guid')->shouldBeCalled()->willReturn('abc');
        $user2->get('guid')->shouldBeCalled()->willReturn('123');
        $this->setParticipant($user1)
          ->setParticipant($user2);
        $this->getIndexKey()->shouldReturn('abc:123');
    }

    public function it_should_permutate_participants_in_order(User $user1, User $user2)
    {
        $user1->get('guid')->shouldBeCalled()->willReturn('456');
        $user2->get('guid')->shouldBeCalled()->willReturn('123');
        $this->setParticipant($user1)
          ->setParticipant($user2);
        $this->getIndexKey()->shouldReturn('123:456');
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
        $this->getIndexKey()->shouldReturn('100000000000000003:100000000000000063:100000000000000599:245660000000000063');
    }

    public function it_should_return_message_and_build_entities(Indexes $db, User $user)
    {
        $this->beConstructedWith($db);

        $messages = [];
        for ($i = 0; $i< 12; $i++) {
            $messages["123456678$i"] = '{
              "guid": 123456678
            }';
        }

        $db->get("object:gathering:conversation:100000000000000063", Argument::type('array'))
          ->willReturn($messages);

        $user->get('guid')->shouldBeCalled()->willReturn('100000000000000063');
        $this->setParticipant($user);

        //$db->getRow('')
        $this->getMessages(12)->shouldHaveCount(12);
        $this->getMessages(12)->shouldHaveKey(1234566781);
    }
}
