<?php

namespace Spec\Minds\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActivitySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\Activity');
    }
}
