<?php

namespace Spec\Minds\Core\Sockets;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BinarySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('phpspec');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Sockets\Binary');
    }

    function it_should_be_converted_to_string()
    {
        $this->__toString()->shouldBe('phpspec');
    }
}
