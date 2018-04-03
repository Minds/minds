<?php

namespace Spec\Minds\Core\Messenger;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Entities\Conversation;
use Minds\Core\Data\Cassandra\Thrift\Entities;
use Minds\Core\Data\Cassandra\Thrift\Indexes;
use Minds\Core\Security\ACL;

class MessagesSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Messenger\Messages');
    }

    function it_should_return_messages(
        Entities $entities,
        Indexes $indexes,
        Conversation $conversation,
        ACL $acl
    )
    {
        $this->beConstructedWith($entities, $indexes, $acl);

        $this->setConversation($conversation);

        $conversation->setGuid(null)
            ->shouldBeCalled();

        $conversation->getGuid()
            ->shouldBeCalled()
            ->willReturn('guid1:guid2');

        $indexes->get('object:gathering:conversation:guid1:guid2', Argument::any())
            ->shouldBeCalled()
            ->willReturn([
                0 => json_encode([
                    'guid' => 1,
                    'message:guid1' => 'foobar'
                ]),
                1 => json_encode([
                    'guid' => 2,
                    'message:guid3' => 'not for you'
                ])
            ]);
        
        $acl->read(Argument::that(function($entity) {
                return $entity->getGuid() === 1;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $acl->read(Argument::that(function($entity) {
                return $entity->getGuid() === 2;
            }))
            ->shouldBeCalled()
            ->willReturn(false);
        
        $this->getMessages()
            ->shouldHaveCount(1);
    }
}
