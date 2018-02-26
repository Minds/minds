<?php

namespace Spec\Minds\Core\Boost;

use Minds\Core\Blockchain\Transactions\Manager;
use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Config\Config;
use Minds\Core\Boost\Pending;
use Minds\Core\Di\Di;
use Minds\Core\Payments\Stripe\Stripe;
use Minds\Core\Util\BigNumber;
use Minds\Entities\Boost\Network;
use Minds\Entities\Boost\Peer;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    /** @var Transactions */
    protected $offchainTransactions;

    /** @var Stripe */
    protected $stripePayments;

    /** @var Ethereum */
    protected $eth;
    
    /** @var Manager */
    protected $txManager;

    /** @var Repository */
    protected $txRepository;

    /** @var Config */
    protected $config;

    function let(
        Transactions $offchainTransactions,
        Stripe $stripePayments,
        Ethereum $eth,
        Manager $txManager,
        Repository $txRepository,
        Config $config
    )
    {
        $this->offchainTransactions = $offchainTransactions;
        $this->stripePayments = $stripePayments;
        $this->eth = $eth;
        $this->txManager = $txManager;
        $this->txRepository = $txRepository;
        $this->config = $config;

        $this->beConstructedWith(
            $this->offchainTransactions,
            $this->stripePayments,
            $this->eth,
            $this->txManager,
            $this->txRepository,
            $this->config
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Payment');
    }

    function it_should_pay_with_tokens()
    {
        $paymentMethodNonce = [
            'address' => '0xaddress',
            'txHash' => '0xTX',
        ];

        $boost = new Network();
        $boost->setBidType('tokens')
            ->setBid((string) BigNumber::toPlain(5, 18))
            ->setOwner(new User);

        $this->txManager->add(Argument::that(function($transaction) {
                return $transaction->getTx() == '0xTX'
                    && $transaction->getWalletAddress() == '0xaddress';
            }))
            ->willReturn('0xTX');

        $this
            ->pay($boost, $paymentMethodNonce)
            ->shouldReturn('0xTX');
    }

    /*function it_should_charge_peer_with_tokens(Peer $boost)
    {
        $boost->getMethod()->willReturn('tokens');

        $this->boostPending->approve($boost)
            ->shouldNotBeCalled();

        $this
            ->charge($boost)
            ->shouldReturn(true);
    }*/


    function it_should_refund_with_tokens()
    {
        $user = new User;
        $user->guid = 123;

        $boost = new Network();
        $boost->setBidType('tokens')
            ->setOwner($user)
            ->setTransactionId('0xTXID');

        $boostTransaction = new Transaction;
        $boostTransaction->setWalletAddress('0xBOOSTERADDR');

        $this->txRepository->get(123, '0xTXID')
            ->willReturn($boostTransaction);

        $this->eth->sendRawTransaction(Argument::any(), Argument::any())
            ->shouldBeCalled();
        $this->eth->encodeContractMethod("reject(uint256)", Argument::any())
            ->shouldBeCalled();

        $this->txManager->add(Argument::that(function($transaction) {
                return $transaction->getWalletAddress() == '0xBOOSTERADDR';
            }))
            ->willReturn('0xREFUND');

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }

    /*function it_should_refund_peer_with_tokens(Peer $boost)
    {

        $boost->getMethod()->willReturn('tokens');

        $this->boostPending->reject($boost)
            ->shouldNotBeCalled();

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }*/

}
