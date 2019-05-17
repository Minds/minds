<?php

namespace Spec\Minds\Core\Reports\Summons;

use Minds\Core\Reports\Summons\Cohort;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CohortSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Cohort::class);
    }
}
