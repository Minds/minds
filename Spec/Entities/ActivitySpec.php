<?php

namespace Spec\Minds\Entities;

use Minds\Core\Di\Di;
use Minds\Core\Wire\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActivitySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\Activity');
    }

    public function it_has_wire_totals(Repository $repository)
    {
        $repository->getSumByEntity(Argument::any(), Argument::any())
            ->shouldBeCalled()
            ->willReturn(10);

        Di::_()->bind('Wire\Repository', function ($di) use ($repository) {
            return $repository->getWrappedObject();
        });

        $this->beConstructedWith(null);
        $this->guid = '123';
        $this->getWireTotals()->shouldBeLike([
            'points' => 10,
            'usd' => 10
        ]);
    }
}