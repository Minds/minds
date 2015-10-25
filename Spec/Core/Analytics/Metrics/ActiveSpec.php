<?php

namespace Spec\Minds\Core\Analytics\Metrics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActiveSpec extends ObjectBehavior {

  function it_is_initializable(){
    $this->shouldHaveType('Minds\Core\Analytics\Metrics\Active');
  }

}
