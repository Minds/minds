<?php

namespace Spec\Minds\Core\Reports\Summons;

use Minds\Core\Reports\Summons\Pool;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PoolSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Pool::class);
    }
}
