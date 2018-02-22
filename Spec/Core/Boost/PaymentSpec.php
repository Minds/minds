<?php

namespace Spec\Minds\Core\Boost;

use Minds\Core\Blockchain\Transactions\Manager;
use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\Boost\Pending;
use Minds\Core\Di\Di;
use Minds\Core\Payments\Stripe\Stripe;
use Minds\Entities\Boost\Network;
use Minds\Entities\Boost\Peer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    /** @var Transactions */
    protected $offchainTransactions;

    /** @var Stripe */
    protected $stripePayments;

    /** @var Manager */
    protected $txManager;

    /** @var Pending */
    protected $boostPending;

    function let(Transactions $offchainTransactions, Stripe $stripePayments, Manager $txManager, Pending $pending)
    {
        $this->offchainTransactions = $offchainTransactions;
        $this->stripePayments = $stripePayments;
        $this->txManager = $txManager;
        $this->boostPending = $pending;
        $this->beConstructedWith($offchainTransactions, $stripePayments, $txManager, $pending);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Payment');
    }

    function it_should_pay_with_tokens(Network $boost)
    {
        /*Di::_()->bind('Boost\Pending', function () use ($pending) {
            return $pending->getWrappedObject();
        });

        $paymentMethodNonce = [ 'txHash' => '0xTX' ];

        $boost->getBidType()->willReturn('tokens');

        $pending->add($paymentMethodNonce['txHash'], $boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->pay($boost, $paymentMethodNonce)
            ->shouldReturn('0xTX');*/
    }

    function it_should_charge_with_tokens(Network $boost)
    {

        $boost->getBidType()->willReturn('tokens');

        $this->boostPending->approve($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->charge($boost)
            ->shouldReturn(true);
    }

    function it_should_charge_peer_with_tokens(Peer $boost)
    {
        $boost->getMethod()->willReturn('tokens');

        $this->boostPending->approve($boost)
            ->shouldNotBeCalled();

        $this
            ->charge($boost)
            ->shouldReturn(true);
    }


    function it_should_refund_with_tokens(Network $boost)
    {

        $boost->getBidType()->willReturn('tokens');

        $this->boostPending->reject($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }

    function it_should_refund_peer_with_tokens(Peer $boost)
    {

        $boost->getMethod()->willReturn('tokens');

        $this->boostPending->reject($boost)
            ->shouldNotBeCalled();

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }
}
