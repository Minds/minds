<?php

namespace Spec\Minds\Core\Pages;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MenuSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Pages\Menu');
    }
}
