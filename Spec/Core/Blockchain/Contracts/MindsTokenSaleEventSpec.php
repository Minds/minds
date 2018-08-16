<?php

namespace Spec\Minds\Core\Blockchain\Contracts;

use Minds\Core\Blockchain\Contracts\MindsTokenSaleEvent;
use PhpSpec\ObjectBehavior;

class MindsTokenSaleEventSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('0x123');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MindsTokenSaleEvent::class);
    }

    function it_should_get_the_abi()
    {
        $this->getABI()->shouldBeArray();
    }
}
