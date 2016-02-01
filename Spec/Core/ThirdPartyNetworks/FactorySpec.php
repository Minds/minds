<?php

namespace Spec\Minds\Core\ThirdPartyNetworks;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\ThirdPartyNetworks\Factory');
    }
}
