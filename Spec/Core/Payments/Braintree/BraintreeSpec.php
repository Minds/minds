<?php

namespace Spec\Minds\Core\Payments\Braintree;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BraintreeSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(
          new \Braintree_Configuration(),
          new \Braintree_ClientToken(),
          \Braintree_Transaction::factory([]),
          new \Braintree_TransactionSearch(),
          \Braintree_MerchantAccount::factory([])
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\Braintree\Braintree');
    }
}
