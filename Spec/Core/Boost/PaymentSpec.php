<?php

namespace Spec\Minds\Core\Boost;

use Minds\Core\Boost\Pending;
use Minds\Core\Di\Di;
use Minds\Entities\Boost\Network;
use Minds\Entities\Boost\Peer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Payment');
    }

    function it_should_pay_with_tokens(
        Network $boost,
        Pending $pending
    )
    {
        Di::_()->bind('Boost\Pending', function () use ($pending) {
            return $pending->getWrappedObject();
        });

        $paymentMethodNonce = [ 'txHash' => '0xTX' ];

        $boost->getBidType()->willReturn('tokens');

        $pending->add($paymentMethodNonce['txHash'], $boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->pay($boost, $paymentMethodNonce)
            ->shouldReturn('0xTX');
    }

    function it_should_charge_with_tokens(
        Network $boost,
        Pending $pending
    )
    {
        Di::_()->bind('Boost\Pending', function () use ($pending) {
            return $pending->getWrappedObject();
        });

        $boost->getBidType()->willReturn('tokens');

        $pending->approve($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->charge($boost)
            ->shouldReturn(true);
    }

    function it_should_charge_peer_with_tokens(
        Peer $boost,
        Pending $pending
    )
    {
        Di::_()->bind('Boost\Pending', function () use ($pending) {
            return $pending->getWrappedObject();
        });

        $boost->getMethod()->willReturn('tokens');

        $pending->approve($boost)
            ->shouldNotBeCalled();

        $this
            ->charge($boost)
            ->shouldReturn(true);
    }


    function it_should_refund_with_tokens(
        Network $boost,
        Pending $pending
    )
    {
        Di::_()->bind('Boost\Pending', function () use ($pending) {
            return $pending->getWrappedObject();
        });

        $boost->getBidType()->willReturn('tokens');

        $pending->reject($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }

    function it_should_refund_peer_with_tokens(
        Peer $boost,
        Pending $pending
    )
    {
        Di::_()->bind('Boost\Pending', function () use ($pending) {
            return $pending->getWrappedObject();
        });

        $boost->getMethod()->willReturn('tokens');

        $pending->reject($boost)
            ->shouldNotBeCalled();

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }
}
