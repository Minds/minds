<?php

namespace Spec\Minds\Core\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Security\AbuseGuard\Aggregates;
use Minds\Core\Security\AbuseGuard\AccusedEntity;

class AbuseGuardSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\AbuseGuard');
    }

    function it_should_get_total_scores(Aggregates $aggregates)
    {
        $aggregates->setPeriod(Argument::type('int'), Argument::type('int'))->shouldBeCalled();
        $aggregates->fetch()->willReturn([
            'comment' => [
                [ 'guid' => 1, 'count' => 20 ],
                [ 'guid' => 2, 'count' => 1 ]
            ],
            'vote:down' => [
                [ 'guid' => 3, 'count' => 5 ]
            ]
        ]);

        $this->beConstructedWith($aggregates);
        
        $this->getScores()->shouldReturn($this);
        $this->getTotal()->shouldReturn(3);
    }

    function it_should_return_correct_total_accused(Aggregates $aggregates)
    {
        $aggregates->setPeriod(Argument::type('int'), Argument::type('int'))->shouldBeCalled();
        $aggregates->fetch()->willReturn([
            'comment' => [
                [ 'guid' => 1, 'count' => 20 ],
                [ 'guid' => 2, 'count' => 1 ]
            ],
            'vote:down' => [
                [ 'guid' => 3, 'count' => 5 ],
                [ 'guid' => 2, 'count' => 30 ]
            ]
        ]);

        $this->beConstructedWith($aggregates);
        
        $this->getScores()->shouldReturn($this);
        $this->getTotal()->shouldReturn(3);
        $this->getTotalAccused()->shouldReturn(2);
    }

}
