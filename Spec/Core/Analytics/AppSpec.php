<?php

namespace Spec\Minds\Core\Analytics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Interfaces\AnalyticsMetric;

class AppSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\App');
    }

    public function it_should_set_a_metric(AnalyticsMetric $metric)
    {
        $this->setMetric($metric);
    }

    public function it_should_throw_on_no_metric()
    {
        $this->shouldThrow('\Exception')->during('setMetric', array('foobar'));
    }

    public function it_should_set_a_metric_key()
    {
        $this->setKey("mykey");
    }

    public function it_should_get_data_for_a_metric_period(AnalyticsMetric $metric)
    {
        $data = array(array('timestamp' => time(), 'date' => date('d-m-Y'), 'total' => 10));
        $metric->get(Argument::type('int'), Argument::type('string'), Argument::any())->willReturn($data);
        $metric->setNamespace(Argument::type('string'))->willReturn();
        $metric->setKey(Argument::any())->willReturn();

        $this->setMetric($metric);
        $this->get(3, "day")->shouldReturn($data);
    }

    public function it_should_increment_a_metric(AnalyticsMetric $metric)
    {
        $metric->setNamespace(Argument::type('string'))->willReturn();
        $metric->setKey(Argument::any())->willReturn();
        $metric->increment()->willReturn(true);

        $this->setMetric($metric);
        $this->increment()->shouldReturn(true);
    }
}
