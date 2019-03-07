<?php

namespace Spec\Minds\Core\Http\Curl;

use Minds\Core\Http\Curl\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }
}
