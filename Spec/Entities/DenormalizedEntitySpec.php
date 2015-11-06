<?php

namespace Spec\Minds\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DenormalizedEntitySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\DenormalizedEntity');
    }

    function it_returns_a_guid(){
        $this->getGuid()->shouldBeNumeric();
    }
}
