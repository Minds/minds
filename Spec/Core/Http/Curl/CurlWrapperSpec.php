<?php

namespace Spec\Minds\Core\Http\Curl;

use Minds\Core\Http\Curl\CurlWrapper;
use PhpSpec\ObjectBehavior;

class CurlWrapperSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CurlWrapper::class);
        $this->getHandle()->shouldNotBeNull();
    }
}
