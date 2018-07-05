<?php

namespace Spec\Minds\Core\Email;

use Minds\Core\Queue\RabbitMQ\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PHPMailer;

use Minds\Core\Email\SpamFilter;
use Minds\Core\Email\Message;

class MailerSpec extends ObjectBehavior
{
    /** @var Client */
    protected $queue;

    function let(PHPMailer $mailer, Client $queue, SpamFilter $filter)
    {
        $this->beConstructedWith($mailer, $queue, $filter);
        $this->queue = $queue;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Mailer');
    }

    function it_should_not_send_a_blacklist_domain(Message $message)
    {
        $message->to = [['email' => 'you@yomail.com', 'name' => 'Spam']];
        $message->from = ['email' => 'me@minds.com', 'name' => 'Sender'];
        $this->send($message);

        $this->getStats()->shouldHaveKeyWithValue('failed', 1);
    }

    function it_should_queue_a_message(Message $message)
    {
        $this->queue->setQueue('Email')
            ->shouldBeCalled()
            ->willReturn($this->queue);

        $this->queue->send(Argument::any())
            ->shouldBeCalled();

        $this->queue($message);
    }

    function it_should_queue_a_message_into_the_priority_queue(Message $message)
    {
        $this->queue->setQueue('PriorityEmail')
            ->shouldBeCalled()
            ->willReturn($this->queue);

        $this->queue->send(Argument::any())
            ->shouldBeCalled();

        $this->queue($message, true);
    }
}
