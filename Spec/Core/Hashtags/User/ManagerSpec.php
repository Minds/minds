<?php

namespace Spec\Minds\Core\Hashtags\User;

use Minds\Core\Hashtags\User\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }
}
