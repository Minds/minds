<?php

namespace Spec\Minds\Core\Di;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DiSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Di\Di');
    }

    function it_should_bind_a_function()
    {
        $this->bind("bindFunction", function($di){
            return time();
        });
        $this->get('bindFunction')->shouldEqual(time());
        $this->get('bindFunction')->shouldNotEqual(123);
    }

    function it_should_bind_a_factory()
    {
        $this->bind("bindFactory", function($di){
            return rand(0,9999999999);
        }, ['useFactory'=>true]);
        $value = $this->get('bindFactory');
        $this->get('bindFactory')->shouldEqual($value);
        $this->get('bindFactory')->shouldNotEqual(rand(0,999999999));
    }

    function it_should_bind_an_immutable()
    {
        $this->bind("bindImmutable", function($di){
            return "I can't be made to change.";
        }, ['immutable'=>true]);
        $this->get("bindImmutable")->shouldEqual("I can't be made to change.");
        $this->shouldThrow('Minds\Core\Di\ImmutableException')->duringBind("bindImmutable", function($di){
            return "I am unique.";
        }, ['immutable'=>true]);
    }
}
