<?php

namespace Spec\Minds\Core\Reports\Summons;

use Minds\Core\Reports\Summons\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }
}
