<?php

namespace Spec\Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Reports\Verdict\Delegates\NotificationDelegate;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotificationDelegateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NotificationDelegate::class);
    }
}
