<?php

namespace Spec\Minds\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuidSpec extends ObjectBehavior {

    function it_is_initializable(){
        $this->shouldHaveType('Minds\Core\Guid');
    }

    function it_should_return_a_guid(){
        $this::build()->shouldBeString();
    }
}
