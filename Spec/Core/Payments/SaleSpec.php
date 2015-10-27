<?php

namespace Spec\Minds\Core\Payments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SaleSpec extends ObjectBehavior {

  function it_is_initializable(){
    $this->shouldHaveType('Minds\Core\Payments\Sale');
  }

}
