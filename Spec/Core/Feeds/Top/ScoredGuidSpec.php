<?php

namespace Spec\Minds\Core\Feeds\Top;

use Minds\Core\Feeds\Top\ScoredGuid;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScoredGuidSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ScoredGuid::class);
    }

    function it_should_set_score_as_a_number()
    {
        $this->setScore('500.1');

        $this->getScore()
            ->shouldReturn(500.1);
    }
}
