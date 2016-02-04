<?php

namespace Spec\Minds\Core\Payments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Config;

class FactorySpec extends ObjectBehavior
{
    public function let()
    {

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\Factory');
    }

    public function it_should_build_a_service()
    {
        $this::build('braintree', ['gateway'=>'merchants'])->shouldImplement('Minds\Core\Payments\PaymentServiceInterface');
    }

    public function it_should_throw_an_exception_if_service_doesnt_exist()
    {
        $this->shouldThrow('\Exception')->during('build', ['foobar']);
    }
}
