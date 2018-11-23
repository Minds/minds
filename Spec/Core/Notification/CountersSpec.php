<?php

namespace Spec\Minds\Core\Notification;

use Minds\Core\Notification\Counters;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CountersSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Counters::class);
    }

}
