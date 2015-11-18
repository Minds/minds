<?php

namespace Spec\Minds\Helpers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotificationsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Helpers\Notifications');
    }
}
