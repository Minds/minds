<?php

namespace Spec\Minds\Core\Email;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MessageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Message');
    }
}
