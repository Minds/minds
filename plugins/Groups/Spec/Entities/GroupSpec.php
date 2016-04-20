<?php

namespace Groups\Spec\Minds\Plugin\Groups\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GroupSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Plugin\Groups\Entities\Group');
    }
}
