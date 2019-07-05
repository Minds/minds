<?php

namespace Spec\Minds\Core\Channels\Delegates\Artifacts;

use Minds\Core\Channels\Delegates\Artifacts\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }
}
