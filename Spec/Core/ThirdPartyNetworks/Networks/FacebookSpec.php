<?php

namespace Spec\Minds\Core\ThirdPartyNetworks\Networks;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FacebookSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\ThirdPartyNetworks\Networks\Facebook');
    }
}
