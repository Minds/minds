<?php

namespace Spec\Minds\Core\Di;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Di\Provider');
    }

    //function it_should_have_a_di_property()
    //{
    //    $this->di->shouldHaveType('Minds\Core\Di\Di');
    //}
}
