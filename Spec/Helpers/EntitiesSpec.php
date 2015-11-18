<?php

namespace Spec\Minds\Helpers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntitiesSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Helpers\Entities');
    }

    public function it_should_build_a_setter_method()
    {
        $this::buildSetter("setter")->shouldReturn("setSetter");
    }

    public function it_should_build_a_setter_method_with_camelcases()
    {
        $this::buildSetter("setterIsBetter")->shouldReturn("setSetterIsBetter");
    }

    public function it_should_build_a_setter_method_with_underscore_transformation()
    {
        $this::buildSetter("setter_is_better")->shouldReturn("setSetterIsBetter");
    }

    public function it_should_build_a_getter_method()
    {
        $this::buildGetter("getter")->shouldReturn("getGetter");
    }

    public function it_should_build_a_getter_method_with_camelcases()
    {
        $this::buildGetter("getterIsBetter")->shouldReturn("getGetterIsBetter");
    }

    public function it_should_build_a_getter_method_with_underscore_transformation()
    {
        $this::buildGetter("getter_is_better")->shouldReturn("getGetterIsBetter");
    }
}
