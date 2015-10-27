<?php

namespace Spec\Minds\Core\Payments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MerchantSpec extends ObjectBehavior {

  function it_is_initializable(){
    $this->shouldHaveType('Minds\Core\Payments\Merchant');
  }

}
