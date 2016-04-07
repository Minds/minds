<?php

namespace Spec\Minds\Core\Analytics\Metrics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Call;
use Minds\Core\Analytics\Timestamps;

class RetentionSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\Metrics\Retention');
    }

    function it_should_increment_the_metric(Call $db)
    {
        $this->beConstructedWith($db);

        $timestamps = Timestamps::span(30, 'day');

        //mock the calls that are expected
        $db->getRow(Argument::containingString(':signup'), ['limit'=>10000])->willReturn(['foo'=>time(), 'bar'=>time(), 'foobar'=>time()]);
        $db->getRow(Argument::containingString(':active'), ['limit'=>10000])->willReturn(['foo'=>time(), 'bar'=>time(), 'barfoo'=>time()]);

        $now = Timestamps::span(2, 'day')[0];
        foreach([1,3,7,28] as $x){
            $db->insert("analytics:retention:$x:" . $now, ['foo'=>time(), 'bar'=>time()])->willReturn("analytics:retention:$x");
        }

        $this->increment()->shouldReturn(true);
    }

    function it_should_return_metrics(Call $db)
    {
        $this->beConstructedWith($db);

        $timestamps = Timestamps::span(30, 'day');

        //mock the calls that are expected
        $db->countRow(Argument::containingString(':signup'))->willReturn(100)->shouldBeCalledTimes(4 * 2); //actually called twice
        $db->countRow(Argument::containingString(':retention:1'))->willReturn(50)->shouldBeCalledTimes(1 * 2);
        $db->countRow(Argument::containingString(':retention:3'))->willReturn(40)->shouldBeCalledTimes(1 * 2);
        $db->countRow(Argument::containingString(':retention:7'))->willReturn(20)->shouldBeCalledTimes(1 * 2);
        $db->countRow(Argument::containingString(':retention:28'))->willReturn(10)->shouldBeCalledTimes(1 * 2);

        $return = $this->get(1);
        $return->shouldBeArray();
        $return->shouldHaveCount(2); //actually always returns 2..
        $return[0]->shouldHaveCount(4);
        $return[0]['total']->shouldBe((0.5+0.4+0.2+0.1) / 4);
        $return[0]['totals'][0]['total']->shouldBe(0.5);
        $return[0]['totals'][1]['total']->shouldBe(0.4);
        $return[0]['totals'][2]['total']->shouldBe(0.2);
        $return[0]['totals'][3]['total']->shouldBe(0.1);
    }

}
