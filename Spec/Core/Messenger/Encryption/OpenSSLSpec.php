<?php

namespace Spec\Minds\Core\Messenger\Encryption;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use Minds\Entities\Message;
use Minds\Entities\Conversation;
use Minds\Core\Messenger\Keystore;

class OpenSSLSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Messenger\Encryption\OpenSSL');
    }

    public function it_should_set_a_message_entity(Message $message)
    {
        $this->setMessage($message)->getMessage()->shouldHaveType('Minds\Entities\Message');
    }

    public function it_should_encrypt_a_message(Message $message, Conversation $conversation, Keystore $keystore,
      User $user1, User $user2)
    {
        $this->beConstructedWith($keystore);

        $keypair = $this->generateKeypair('foobar');

        $keystore->setUser(Argument::any())->shouldBeCalled()->willReturn($keystore);
        $keystore->getPublicKey()->willReturn($keypair['public']);

        $user1->guid = 'abc';
        $user2->guid = 'def';

        $conversation->getParticipants()->willReturn(['abc' => $user1, 'def' => $user2]);
        $this->setConversation($conversation);

        $message->getMessage()->shouldBeCalled()->willReturn("helloworld");
        $message->setMessages(Argument::type('array'))
          ->shouldBeCalled();
        $this->setMessage($message);
        $this->encrypt();
    }

    /*function it_should_decrypt_a_message(Message $message, Keystore $keystore, User $user)
    {
        $this->beConstructedWith($keystore);

        $keypair = $this->generateKeypair('foobar');

        $keystore->setUser(Argument::any())->shouldBeCalled()->willReturn($keystore);
        $keystore->getPrivateKey()->willReturn($keypair['public']);

        $user1->guid = 'abc';
        $user2->guid = 'def';

        $this->setConversation($conversation);

        $message->getMessage('abc')->willReturn("");

        $this->setUser($user);
        $this->setMessage($message);
        $this->encrypt();
    }*/
}
