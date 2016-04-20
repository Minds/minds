<?php

namespace Spec\Minds\Core\Payments\Braintree;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Config\Config;

use Braintree_Configuration;

class BraintreeSpec extends ObjectBehavior
{


    function it_is_initializable(Braintree_Configuration $btConfig, Config $config)
    {
        $this->beConstructedWith($btConfig, $config);
        $this->shouldHaveType('Minds\Core\Payments\Braintree\Braintree');
    }
}
