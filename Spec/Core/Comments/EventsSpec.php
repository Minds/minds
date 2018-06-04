<?php

namespace Spec\Minds\Core\Comments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Events');
    }
}
