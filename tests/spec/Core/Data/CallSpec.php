<?php

namespace Spec\Minds\Core\Data;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

use phpcassa\ColumnFamily;

class CallSpec extends ObjectBehavior {

  function let(){
    $this->beConstructedWith("phpspec", "phpspec");
  }

  function it_is_initializable(){
    $this->shouldHaveType('Minds\Core\Data\Call');
  }

}
