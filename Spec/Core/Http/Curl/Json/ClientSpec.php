<?php

namespace Spec\Minds\Core\Http\Curl\Json;

use Minds\Core\Http\Curl\Json\Client;
use PhpSpec\ObjectBehavior;
use Minds\Core\Http\Curl\CurlWrapper;

class ClientSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
        $this->getCurl()->shouldHaveType(CurlWrapper::class);
    }
}
