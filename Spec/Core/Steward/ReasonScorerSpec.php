<?php

namespace Spec\Minds\Core\Steward;

use Minds\Core\Steward\ReasonScorer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReasonScorerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ReasonScorer::class);
    }
}
