<?php

namespace Spec\Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Reports\Verdict\Delegates\MetricsDelegate;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetricsDelegateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MetricsDelegate::class);
    }
}
