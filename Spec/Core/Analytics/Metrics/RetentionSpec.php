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

        $timestamps = Timestamps::span(28, 'day');

        //mock the calls that are expected
        $db->getRow(Argument::containingString(':signup'), ['limit'=>10000])->willReturn(['foo'=>time(), 'bar'=>time(), 'foobar'=>time()]);
        $db->getRow(Argument::containingString(':active'), ['limit'=>10000])->willReturn(['foo'=>time(), 'bar'=>time(), 'barfoo'=>time()]);

        foreach([1,3,7,28] as $x){
            $db->insert("analytics:retention:$x:" . Timestamps::get(['day'])['day'], ['foo'=>time(), 'bar'=>time()])->willReturn("analytics:retention:$x");
        }

        $this->increment()->shouldReturn(true);
    }

}
