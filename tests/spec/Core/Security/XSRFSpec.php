<?php

namespace Spec\Minds\Core\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XSRFSpec extends ObjectBehavior {

    function it_is_initializable(){
        $this->shouldHaveType('Minds\Core\Security\XSRF');
    }

}
