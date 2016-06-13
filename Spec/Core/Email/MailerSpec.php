<?php

namespace Spec\Minds\Core\Email;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PHPMailer;

use Minds\Core\Email\SpamFilter;
use Minds\Core\Email\Message;

class MailerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Mailer');
    }

    function it_should_not_send_a_blacklist_domain(PHPMailer $mailer, SpamFilter $filter, Message $message)
    {
        $this->beConstructedWith($mailer, null, $filter);

        $message->to = [[ 'email' => 'you@yomail.com', 'name' => 'Spam' ]];
        $message->from = [ 'email' => 'me@minds.com', 'name' => 'Sender' ];
        $this->send($message);

        $this->getStats()->shouldHaveKeyWithValue('failed', 1);
    }
}
