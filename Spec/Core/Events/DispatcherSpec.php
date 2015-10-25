<?php

namespace Spec\Minds\Core\Events;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class DispatcherSpec extends ObjectBehavior {

    function it_is_initializable(){
      $this->shouldHaveType('Minds\Core\Events\Dispatcher');
    }

    function it_should_reject_an_event_listener(){
      $this::register("foo-event", "all", function(){})->shouldReturn(true);
    }

    //function it_should_unregister_an_event_listener(){
    //  $this::unregister("foo-event", "all");
    //}

    function it_should_trigger_an_event(){
      $this::register("foo-event", "all", function($e) { $e->setResponse("called!"); })->shouldReturn(true);
      $this::trigger("foo-event", "all", array())->shouldReturn("called!");
    }

    function it_should_trigger_an_event_with_parameters(){
      $this::register("foo-event", "all", function($e) { $e->setResponse($e->getParameters()); })->shouldReturn(true);

      $params = array("foo" => "bar", "one" => 1, 2 => "two", "true" => true);
      $this::trigger("foo-event", "all", $params)->shouldReturn($params);
    }

}
