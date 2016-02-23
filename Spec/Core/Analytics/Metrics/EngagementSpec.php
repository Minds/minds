<?php

namespace Spec\Minds\Core\Analytics\Metrics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Call;

class EngagementSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\Metrics\Engagement');
    }

    function it_should_return_metrics(Call $db)
    {
        $this->beConstructedWith($db);

        //mock the calls that are expected
        $db->countRow(Argument::containingString(':active:month'))->willReturn(1000)->shouldBeCalledTimes(3);
        $db->countRow(Argument::containingString(':active:day'))->willReturn(500)->shouldBeCalledTimes(3);

        $return = $this->get(3);
        $return->shouldBeArray();
        $return->shouldHaveCount(3);
        $return[0]->shouldHaveCount(3);
        $return[0]['total']->shouldBe(0.5);
    }

}
