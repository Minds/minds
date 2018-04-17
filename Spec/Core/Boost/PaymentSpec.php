<?php

namespace Spec\Minds\Core\Boost;

use Minds\Core\Blockchain\Transactions\Manager;
use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Blockchain\Wallets\OffChain\Cap;
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
use Minds\Core\Data\Locks\Redis as Locks;
 
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

    /** @var Cap */
    protected $offchainCap;

    /** @var Locks */
    protected $locks;

    function let(
        Transactions $offchainTransactions,
        Stripe $stripePayments,
        Ethereum $eth,
        Manager $txManager,
        Repository $txRepository,
        Config $config,
        Cap $offchainCap,
        Locks $locks
    )
    {
        $this->offchainTransactions = $offchainTransactions;
        $this->stripePayments = $stripePayments;
        $this->eth = $eth;
        $this->txManager = $txManager;
        $this->txRepository = $txRepository;
        $this->config = $config;
        $this->offchainCap = $offchainCap;
        $this->locks = $locks;

        $this->beConstructedWith(
            $this->stripePayments,
            $this->eth,
            $this->txManager,
            $this->txRepository,
            $this->config
        );

        Di::_()->bind('Blockchain\Wallets\OffChain\Transactions', function () use ($offchainTransactions) {
            return $offchainTransactions->getWrappedObject();
        });

        Di::_()->bind('Blockchain\Wallets\OffChain\Cap', function () use ($offchainCap) {
            return $offchainCap->getWrappedObject();
        });
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Payment');
    }

    function it_should_pay_with_onchain_tokens(
        Network $boost,
        User $boost_owner
    )
    {
        $paymentMethodNonce = [
            'method' => 'onchain',
            'address' => '0xaddress',
            'txHash' => '0xTX',
        ];

        $boost->getHandler()
            ->willReturn('network');

        $boost->getGuid()
            ->willReturn(1000);

        $boost->getBidType()
            ->willReturn('tokens');

        $boost->getBid()
            ->willReturn((string) BigNumber::toPlain(5, 18));

        $boost->getOwner()
            ->willReturn($boost_owner);

        $this->txManager
            ->add(Argument::that(function($transaction) {
                return $transaction->getTx() == '0xTX'
                    && $transaction->getWalletAddress() == '0xaddress';
            }))
            ->willReturn('0xTX');

        $this
            ->pay($boost, $paymentMethodNonce)
            ->shouldReturn('0xTX');
    }

    function it_should_pay_with_offchain_tokens(
        Network $boost,
        User $boost_owner
    )
    {
        $bid = (string) BigNumber::toPlain(5, 18);

        $paymentMethodNonce = [
            'method' => 'offchain',
            'address' => 'offchain'
        ];

        $boost->getHandler()
            ->willReturn('network');

        $boost->getGuid()
            ->willReturn(1000);

        $boost->getBidType()
            ->willReturn('tokens');

        $boost->getBid()
            ->willReturn($bid);

        $boost->getOwner()
            ->willReturn($boost_owner);

        $this->offchainTransactions->setAmount((string) BigNumber::_($bid)->neg())
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setType('boost')
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setUser($boost_owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setData([ 'amount' => $bid, 'guid' => 1000, 'handler' => 'network' ])
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainCap->setUser($boost_owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainCap);

        $this->offchainCap->setContract('boost')
            ->shouldBeCalled()
            ->willReturn($this->offchainCap);

        $this->offchainCap->isAllowed($bid)
            ->shouldBeCalled()
            ->willReturn(true);

        $tx = new Transaction();
        $tx->setTx('oc:123');

        $this->offchainTransactions->create()
            ->shouldBeCalled()
            ->willReturn($tx);

        $this
            ->pay($boost, $paymentMethodNonce)
            ->shouldReturn('oc:123');
    }

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
}
