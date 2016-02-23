<?php

namespace Spec\Minds\Core\Analytics\Metrics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Call;
use Minds\Core\Analytics\Timestamps;

class SignupSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\Metrics\Signup');
    }

    function it_should_increment_the_metric(Call $db)
    {
        $this->beConstructedWith($db);
        foreach(Timestamps::get(['day', 'month'])  as $p => $ts){
            $db->insert("analytics:signup:$p:$ts", Argument::type('array'))->willReturn();
        }
        $this->setKey('foobar');
        $this->increment()->shouldReturn(true);
    }

    function it_should_return_a_span_of_metrics(Call $db)
    {
        $this->beConstructedWith($db);
        $timestamps = Timestamps::span(3, 'day');
        foreach($timestamps as $ts){
            $db->countRow("analytics:signup:day:$ts")->willReturn(3);
        }
        $this->get(3, 'day')->shouldHaveCount(3);
        /*$this->get(3, 'day')->shouldContain([
          'ts' => $timestamps[0],
          'date' => date('d-m-Y', $timestamps[0]),
          'total' => 3
        ]);*/
    }

}
