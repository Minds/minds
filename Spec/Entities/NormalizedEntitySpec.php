<?php

namespace Spec\Minds\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NormalizedEntitySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\NormalizedEntity');
    }

    function it_returns_a_guid(){
        $this->getGuid()->shouldBeNumeric();
    }
}
