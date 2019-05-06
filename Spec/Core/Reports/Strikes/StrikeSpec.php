<?php

namespace Spec\Minds\Core\Reports\Strikes;

use Minds\Core\Reports\Strikes\Strike;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StrikeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Strike::class);
    }

    function it_should_return_preferred_urn()
    {
        $this->setTimestamp(1557176817000)
            ->setUserGuid(123)
            ->setReasonCode(2)
            ->setSubReasonCode(5);

        $this->getUrn()
            ->shouldBe("urn:strike:123-1557176817000-2-5");   
    }

}
