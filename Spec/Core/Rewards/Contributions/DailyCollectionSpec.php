<?php

namespace Spec\Minds\Core\Rewards\Contributions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Rewards\Contributions\Contribution;
use Minds\Core\Rewards\Contributions\Sums;

class DailyCollectionSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Contributions\DailyCollection');
    }

    function it_should_return_daily_collections(Sums $sums)
    {
        $this->beConstructedWith($sums);
        $sums->setTimestamp(Argument::any())
            ->willReturn($sums);

        $sums->getScore()->willReturn(10000);

        $this->setContributions([
            (new Contribution())
                ->setUser((string) 123)
                ->setMetric((string) 'votes')
                ->setTimestamp(strtotime('2018-02-14'))
                ->setAmount((int) 1000)
                ->setScore((int) 2000),
            (new Contribution())
                ->setUser((string) 123)
                ->setMetric((string) 'comments')
                ->setTimestamp(strtotime('2018-02-14'))
                ->setAmount((int) 1000)
                ->setScore((int) 2000),
            (new Contribution())
                ->setUser((string) 123)
                ->setMetric((string) 'votes')
                ->setTimestamp(strtotime('2018-02-13'))
                ->setAmount((int) 1000)
                ->setScore((int) 2000)
        ]);

        $export = $this->export();
        $export->shouldHaveCount(2);
        $export[0]['metrics']->shouldHaveCount(2);
        $export[1]['metrics']->shouldHaveCount(1);
    }

}
