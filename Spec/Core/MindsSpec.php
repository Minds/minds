<?php

namespace Spec\Minds\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MindsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Minds');
    }
}
