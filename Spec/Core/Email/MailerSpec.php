<?php

namespace Spec\Minds\Core\Email;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PHPMailer;

class MailerSpec extends ObjectBehavior
{

    function let(PHPMailer $mailer)
    {
        $this->beConstructedWith($mailer, true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Mailer');
    }
}
