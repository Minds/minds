<?php

namespace Spec\Minds\Entities;

use Minds\Core\Di\Di;
use Minds\Core\Wire\Sums;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActivitySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\Activity');
    }

    public function it_has_wire_totals(Sums $sums)
    {
        $sums->setEntity(Argument::any())
            ->willReturn($sums);
        $sums->getEntity()
            ->willReturn(10);

        Di::_()->bind('Wire\Sums', function ($di) use ($sums) {
            return $sums->getWrappedObject();
        });

        $this->beConstructedWith(null);
        $this->guid = '123';
        $this->getWireTotals()->shouldBeLike([
            'tokens' => 10
        ]);
    }
}
