<?php

namespace Spec\Minds\Core\Trending;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Trending\Maps;
use Minds\Core\Trending\Aggregates\Aggregate;
use Minds\Core\Trending\EntityValidator;
use Minds\Core\Trending\Repository;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Trending\Manager');
    }

    function it_should_collect_and_store_trending_entities(
        Repository $repo,
        Aggregate $agg,
        EntityValidator $validator
    )
    {
        $maps = [
            'newsfeed' => [
                'type' => 'activity',
                'subtype' => '',
                'aggregates' => [
                    $agg
                ]
            ]
        ];
        $this->beConstructedWith($repo, $validator, $maps);

        $validator->isValid('123', 'activity', '')
            ->shouldBeCalled()
            ->willReturn(true);

        $validator->isValid('456', 'activity', '')
            ->shouldBeCalled()
            ->willReturn(false);
        
        $validator->isValid('789', 'activity', '')
            ->shouldBeCalled()
            ->willReturn(true);
        
        $agg->setType('activity')->shouldBeCalled();
        $agg->setSubtype('')->shouldBeCalled();
        $agg->setFrom(Argument::any())->shouldBeCalled();
        $agg->setTo(Argument::any())->shouldBeCalled();
        $agg->setLimit(100)->shouldBeCalled();

        $agg->get()
            ->shouldBeCalled()
            ->willReturn([
                123 => 5,
                456 => 10,
                789 => 10
            ]);

        $repo->add('newsfeed', [ 789, 123 ])
            ->shouldBeCalled();
        
        $this->run();
    }
}
