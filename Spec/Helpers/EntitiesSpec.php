<?php

namespace Spec\Minds\Helpers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntitiesSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Helpers\Entities');
    }

    function it_should_build_a_setter_method(){
        $this::buildSetter("setter")->shouldReturn("setSetter");
    }

    function it_should_build_a_setter_method_with_camelcases(){
        $this::buildSetter("setterIsBetter")->shouldReturn("setSetterIsBetter");
    }

    function it_should_build_a_setter_method_with_underscore_transformation(){
        $this::buildSetter("setter_is_better")->shouldReturn("setSetterIsBetter");
    }

    function it_should_build_a_getter_method(){
        $this::buildGetter("getter")->shouldReturn("getGetter");
    }

    function it_should_build_a_getter_method_with_camelcases(){
        $this::buildGetter("getterIsBetter")->shouldReturn("getGetterIsBetter");
    }

    function it_should_build_a_getter_method_with_underscore_transformation(){
        $this::buildGetter("getter_is_better")->shouldReturn("getGetterIsBetter");
    }

}
