<?php

namespace Spec\Minds\Core\Analytics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TimestampsSpec extends ObjectBehavior
{
    public function let()
    {
        date_default_timezone_set('UTC');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\Timestamps');
    }

    public function it_should_return_a_span_of_timestamps_for_10_days()
    {
        $this::span(10, "day")->shouldHaveCount(10);
        $this::span(10, "day")->shouldContain(strtotime('midnight -1 days'));
        $this::span(10, "day")->shouldNotContain(strtotime('midnight -11 days'));
    }

    public function it_should_return_a_span_of_timestamps_for_3_months()
    {
        $this::span(3, "month")->shouldHaveCount(3);
        $this::span(3, "month")->shouldContain(strtotime('midnight first day of this month -1 months'));
        $this::span(3, "month")->shouldNotContain(strtotime('midnight first day of this month -4 months'));
    }
}
