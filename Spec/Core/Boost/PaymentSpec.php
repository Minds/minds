<?php

namespace Spec\Minds\Core\Boost;

use Minds\Core\Blockchain\Services\RatesInterface;
use Minds\Core\Blockchain\Transactions\Manager;
use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Blockchain\Wallets\OffChain\Cap;
use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\Wallets\OffChain\Withholding\Repository as WithholdingRepository;
use Minds\Core\Blockchain\Wallets\OffChain\Withholding\Withholding;
use Minds\Core\Config\Config;
use Minds\Core\Data\Cassandra\Thrift\Lookup;
use Minds\Core\Data\Locks\LockFailedException;
use Minds\Core\Di\Di;
use Minds\Core\Payments\Customer;
use Minds\Core\Payments\Sale;
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

    /** @var Lookup */
    protected $lu;

    /** @var RatesInterface */
    protected $rates;

    /** @var WithholdingRepository */
    protected $withholding;

    function let(
        Transactions $offchainTransactions,
        Stripe $stripePayments,
        Ethereum $eth,
        Manager $txManager,
        Repository $txRepository,
        Config $config,
        Cap $offchainCap,
        Locks $locks,
        Lookup $lu,
        RatesInterface $rates,
        WithholdingRepository $withholding
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
        $this->lu = $lu;
        $this->rates = $rates;
        $this->withholding = $withholding;

        $this->beConstructedWith(
            $this->stripePayments,
            $this->eth,
            $this->txManager,
            $this->txRepository,
            $this->config,
            $this->locks
        );

        Di::_()->bind('Blockchain\Wallets\OffChain\Transactions', function () use ($offchainTransactions) {
            return $offchainTransactions->getWrappedObject();
        });

        Di::_()->bind('Blockchain\Wallets\OffChain\Cap', function () use ($offchainCap) {
            return $offchainCap->getWrappedObject();
        });

        Di::_()->bind('Database\Cassandra\Lookup', function ()
        {
            return $this->lu->getWrappedObject();
        });

        Di::_()->bind('Blockchain\Rates', function () {
            return $this->rates->getWrappedObject();
        });

        Di::_()->bind('Blockchain\Wallets\OffChain\Withholding\Repository', function () {
            return $this->withholding->getWrappedObject();
        });
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Payment');
    }

    function it_should_pay_network_with_money(
        Network $boost,
        User $owner,
        Customer $customer
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('network');

        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('money');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn(10);

        $owner->get('guid')->willReturn(5000);
        $owner->get('referrer')->willReturn(null);

        $this->stripePayments->createCustomer(Argument::type(Customer::class))
            ->shouldBeCalled()
            ->willReturn($customer);

        $customer->getId()
            ->shouldBeCalled()
            ->willReturn('phpspec');

        $this->stripePayments->setSale(Argument::type(Sale::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->pay($boost, 'phpspec')
            ->shouldReturn(true);
    }

    function it_should_throw_if_peer_during_pay_with_money(
        Peer $boost
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('money');

        $this
            ->shouldThrow(new \Exception('Money P2P boosts are not supported'))
            ->duringPay($boost, '~Stripe');
    }

    function it_should_pay_network_with_offchain_tokens(
        Network $boost,
        User $owner
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('network');

        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $bid = (string) BigNumber::toPlain(1, 18);
        $negBid = (string) BigNumber::toPlain(-1, 18);

        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $this->offchainCap->setUser($owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainCap);

        $this->offchainCap->setContract('boost')
            ->shouldBeCalled()
            ->willReturn($this->offchainCap);

        $this->offchainCap->isAllowed($bid)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->offchainTransactions->setAmount($negBid)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setType('boost')
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setUser($owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setData(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $tx = new Transaction();
        $tx->setTx('~OCTX');

        $this->offchainTransactions->create()
            ->shouldBeCalled()
            ->willReturn($tx);

        $owner->get('guid')->willReturn(5000);

        $this
            ->pay($boost, [
                'method' => 'offchain'
            ])
            ->shouldReturn('~OCTX');
    }

    function it_should_throw_if_past_allowed_during_pay_network_with_offchain_tokens(
        Network $boost,
        User $owner
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('network');

        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $bid = (string) BigNumber::toPlain(1, 18);

        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $this->offchainCap->setUser($owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainCap);

        $this->offchainCap->setContract('boost')
            ->shouldBeCalled()
            ->willReturn($this->offchainCap);

        $this->offchainCap->isAllowed($bid)
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->shouldThrow(new \Exception('You are not allowed to spend that amount of coins.'))
            ->duringPay($boost, [
                'method' => 'offchain'
            ]);
    }

    function it_should_pay_peer_with_offchain_tokens(
        Peer $boost,
        User $owner,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $bid = (string) BigNumber::toPlain(1, 18);
        $negBid = (string) BigNumber::toPlain(-1, 18);

        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $this->offchainCap->setUser($owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainCap);

        $this->offchainCap->setContract('boost')
            ->shouldBeCalled()
            ->willReturn($this->offchainCap);

        $this->offchainCap->isAllowed($bid)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->offchainTransactions->setAmount($negBid)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setType('boost')
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setUser($owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setData(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $tx = new Transaction();
        $tx->setTx('~OCTX');

        $this->offchainTransactions->create()
            ->shouldBeCalled()
            ->willReturn($tx);

        $owner->get('guid')->willReturn(5000);
        $destination->get('guid')->willReturn(5001);

        $destination->getPhoneNumberHash()
            ->shouldBeCalled()
            ->willReturn('~PHNNMB');

        $this
            ->pay($boost, [
                'method' => 'offchain'
            ])
            ->shouldReturn('~OCTX');
    }

    function it_should_throw_if_no_rewards_program_during_pay_peer_with_offchain_tokens(
        Peer $boost,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $destination->getPhoneNumberHash()
            ->shouldBeCalled()
            ->willReturn('');

        $this
            ->shouldThrow(new \Exception('Boost target should participate in the Rewards program.'))
            ->duringPay($boost, [
                'method' => 'offchain'
            ]);
    }

    function it_should_pay_network_with_creditcard_offchain_tokens(
        Network $boost,
        User $owner,
        Customer $customer
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('network');

        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $bid = (string) BigNumber::toPlain(1, 18);

        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $this->stripePayments->getCustomer(Argument::type(Customer::class))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->stripePayments->createCustomer(Argument::type(Customer::class))
            ->shouldBeCalled()
            ->willReturn($customer);

        $this->stripePayments->setSale(Argument::type(Sale::class))
            ->shouldBeCalled()
            ->willReturn('~sale');

        $customer->getId()
            ->shouldBeCalled()
            ->willReturn('~cid');

        $this->config->get('blockchain')
            ->shouldBeCalled()
            ->willReturn([
                'token_symbol' => 'TEST'
            ]);

        $this->rates->setCurrency('TEST')
            ->shouldBeCalled()
            ->willReturn($this->rates);

        $this->rates->get()
            ->shouldBeCalled()
            ->willReturn(2);

        $this->txManager->add(Argument::type(Transaction::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $owner->get('guid')->willReturn(5000);

        $this
            ->pay($boost, [
                'method' => 'creditcard',
                'token' => '~TOKEN'
            ])
            ->shouldReturn('creditcard:~sale');
    }

    function it_should_pay_peer_with_creditcard_offchain_tokens(
        Peer $boost,
        User $owner,
        Customer $customer,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $bid = (string) BigNumber::toPlain(1, 18);

        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $destination->getPhoneNumberHash()
            ->shouldBeCalled()
            ->willReturn('~PHONE');

        $destination->get('guid')->willReturn(5001);

        $this->stripePayments->getCustomer(Argument::type(Customer::class))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->stripePayments->createCustomer(Argument::type(Customer::class))
            ->shouldBeCalled()
            ->willReturn($customer);

        $this->stripePayments->setSale(Argument::type(Sale::class))
            ->shouldBeCalled()
            ->willReturn('~sale');

        $customer->getId()
            ->shouldBeCalled()
            ->willReturn('~cid');

        $this->config->get('blockchain')
            ->shouldBeCalled()
            ->willReturn([
                'token_symbol' => 'TEST'
            ]);

        $this->rates->setCurrency('TEST')
            ->shouldBeCalled()
            ->willReturn($this->rates);

        $this->rates->get()
            ->shouldBeCalled()
            ->willReturn(2);

        $this->txManager->add(Argument::type(Transaction::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $owner->get('guid')->willReturn(5000);

        $this
            ->pay($boost, [
                'method' => 'creditcard',
                'token' => '~TOKEN'
            ])
            ->shouldReturn('creditcard:~sale');
    }

    function it_should_throw_if_no_rewards_program_during_pay_peer_with_creditcard_offchain_tokens(
        Peer $boost,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $destination->getPhoneNumberHash()
            ->shouldBeCalled()
            ->willReturn('');

        $this
            ->shouldThrow(new \Exception('Boost target should participate in the Rewards program.'))
            ->duringPay($boost, [
                'method' => 'creditcard'
            ]);
    }

    function it_should_pay_network_with_onchain_tokens(
        Network $boost,
        User $owner
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('network');

        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(1, 18));

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $owner->get('guid')->willReturn(5000);

        $this->txManager->add(Argument::type(Transaction::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->pay($boost, [ 'method' => 'onchain', 'address' => '0xADDR', 'txHash' => '0xTX' ])
            ->shouldReturn('0xTX');
    }

    function it_should_pay_peer_with_onchain_tokens(
        Peer $boost,
        User $owner,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn((string) BigNumber::toPlain(1, 18));

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $owner->get('guid')->willReturn(5000);

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $destination->get('guid')->willReturn(5001);
        $destination->getEthWallet()
            ->shouldBeCalled()
            ->willReturn('0xADDRDEST');

        $this->txManager->add(Argument::type(Transaction::class))
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $this
            ->pay($boost, [ 'method' => 'onchain', 'address' => '0xADDR', 'txHash' => '0xTX' ])
            ->shouldReturn('0xTX');
    }

    function it_should_throw_if_no_rewards_program_during_pay_peer_with_onchain_tokens(
        Peer $boost,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $destination->getEthWallet()
            ->shouldBeCalled()
            ->willReturn('');

        $this
            ->shouldThrow(new \Exception('Boost target should participate in the Rewards program.'))
            ->duringPay($boost, [
                'method' => 'onchain'
            ]);
    }

    function it_should_throw_if_payment_method_is_not_supported_during_pay(
        Network $boost
    )
    {
        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('!!notexisting');

        $this
            ->shouldThrow(new \Exception('Payment Method not supported'))
            ->duringPay($boost, '');
    }

    function it_should_charge_money_boost(
        Network $boost,
        User $owner
    )
    {
        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('money');

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('~stripe');

        $owner->get('referrer')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->stripePayments->chargeSale(Argument::type(Sale::class))
            ->shouldBeCalled()
            ->willReturn(null); // Don't trigger email

        $this
            ->charge($boost)
            ->shouldReturn(null);
    }

    function it_should_charge_peer_offchain_tokens_boost(
        Peer $boost,
        User $owner,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('oc:123');

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $owner->get('guid')->willReturn(5000);

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $destination->get('guid')->willReturn(5001);

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $bid = (string) BigNumber::toPlain(1, 18);
        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $this->offchainTransactions->setAmount($bid)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setType('boost')
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setUser($destination)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setData(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->create()
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->charge($boost)
            ->shouldReturn(true);
    }

    function it_should_charge_network_creditcard_offchain_tokens_boost(
        Network $boost
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('network');

        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('creditcard:123');

        $this->stripePayments->chargeSale(Argument::type(Sale::class))
            ->shouldBeCalled()
            ->willReturn(null); // Avoid email event

        $this
            ->charge($boost)
            ->shouldReturn(null);
    }

    function it_should_charge_peer_creditcard_offchain_tokens_boost(
        Peer $boost,
        User $owner,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('creditcard:123');

        $bid = (string) BigNumber::toPlain(1, 18);
        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $owner->get('guid')->willReturn(5000);

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $destination->get('guid')->willReturn(5001);

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $this->stripePayments->chargeSale(Argument::type(Sale::class))
            ->shouldBeCalled()
            ->willReturn(null); // Avoid email event

        $this->txManager->add(Argument::type(Transaction::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->withholding->add(Argument::type(Withholding::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->charge($boost)
            ->shouldReturn(null);
    }

    function it_should_throw_if_payment_method_is_not_supported_during_charge(
        Network $boost
    )
    {
        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('!!notexisting');

        $this
            ->shouldThrow(new \Exception('Payment Method not supported'))
            ->duringCharge($boost);
    }

    function it_should_refund_network_money_boost(
        Network $boost,
        User $owner
    )
    {
        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('money');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('~stripe');

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $owner->get('referrer')->willReturn(null);

        $this->stripePayments->voidOrRefundSale(Argument::type(Sale::class), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }

    function it_should_refund_network_onchain_tokens_boost(
        Network $boost,
        User $owner
    )
    {
        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('network');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $bid = (string) BigNumber::toPlain(1, 18);
        $negBid = (string) BigNumber::toPlain(1, 18)->neg();
        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $owner->get('guid')->willReturn(1000);

        $transaction = new Transaction();
        $transaction
            ->setWalletAddress('0xADDR')
            ->setAmount($bid);

        $this->txRepository->get(1000, '0xTX')
            ->shouldBeCalled()
            ->willReturn($transaction);

        $this->config->get('blockchain')->willReturn([
            'boost_wallet_pkey' => '0xPKEY',
            'boost_wallet_address' => '0xBOOSTWALLET',
            'boost_address' => '0xBOOST'
        ]);

        $this->eth->encodeContractMethod('reject(uint256)', [
            BigNumber::_(8000)->toHex(true)
        ])
            ->shouldBeCalled()
            ->willReturn('~REJECT_ENCODED');

        $this->eth->sendRawTransaction('0xPKEY', [
            'from' => '0xBOOSTWALLET',
            'to' => '0xBOOST',
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => '~REJECT_ENCODED'
        ])
            ->shouldBeCalled()
            ->willReturn('0xREFUNDTX');

        $this->txManager->add(Argument::that(function (Transaction $tx) use ($negBid) {
            return (
                $tx->getUserGuid() === 1000 &&
                $tx->getWalletAddress() === '0xADDR' &&
                $tx->getContract() === 'boost' &&
                $tx->getAmount() === $negBid &&
                $tx->getCompleted() === false
            );
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }

    function it_should_bypass_refunding_peer_onchain_tokens_boost(
        Peer $boost
    )
    {
        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $this->eth->sendRawTransaction(Argument::cetera())
            ->shouldNotBeCalled();

        $this->txManager->add(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }

    function it_should_refund_network_offchain_tokens_boost(
        Network $boost,
        User $owner
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('network');

        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('oc:123');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $bid = BigNumber::toPlain(1, 18);
        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $owner->get('guid')->willReturn(1000);

        $this->locks->setKey('boost:refund:8000')
            ->shouldBeCalled()
            ->willReturn($this->locks);

        $this->locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->locks->setTTL(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn($this->locks);

        $this->locks->lock()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->offchainTransactions->setAmount($bid)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setType('boost_refund')
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setUser($owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setData(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->create()
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }

    function it_should_refund_peer_offchain_tokens_boost(
        Peer $boost,
        User $owner,
        User $destination
    )
    {
        $boost->getHandler()
            ->shouldBeCalled()
            ->willReturn('peer');

        $boost->getMethod()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('oc:123');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $bid = BigNumber::toPlain(1, 18);
        $boost->getBid()
            ->shouldBeCalled()
            ->willReturn($bid);

        $boost->getOwner()
            ->shouldBeCalled()
            ->willReturn($owner);

        $owner->get('guid')->willReturn(1000);

        $boost->getDestination()
            ->shouldBeCalled()
            ->willReturn($destination);

        $destination->get('guid')->willReturn(1001);

        $this->locks->setKey('boost:refund:8000')
            ->shouldBeCalled()
            ->willReturn($this->locks);

        $this->locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->locks->setTTL(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn($this->locks);

        $this->locks->lock()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->offchainTransactions->setAmount($bid)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setType('boost_refund')
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setUser($owner)
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->setData(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($this->offchainTransactions);

        $this->offchainTransactions->create()
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->refund($boost)
            ->shouldReturn(true);
    }

    function it_should_throw_if_locked_during_network_offchain_boost_refund(
        Network $boost
    )
    {
        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('oc:123');

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn(8000);

        $this->locks->setKey('boost:refund:8000')
            ->shouldBeCalled()
            ->willReturn($this->locks);

        $this->locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->offchainTransactions->create()
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(LockFailedException::class)
            ->duringRefund($boost);
    }

    function it_should_refund_creditcard_tokens_boost(
        Network $boost
    )
    {
        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('tokens');

        $boost->getTransactionId()
            ->shouldBeCalled()
            ->willReturn('creditcard:~stripe');

        $this->stripePayments->voidOrRefundSale(Argument::type(Sale::class), true)
            ->shouldBeCalled()
            ->willReturn('~stripe-refund');

        $this
            ->refund($boost)
            ->shouldReturn('~stripe-refund');
    }

    function it_should_throw_if_payment_method_is_not_supported_during_refund(
        Network $boost
    )
    {
        $boost->getBidType()
            ->shouldBeCalled()
            ->willReturn('!!notexisting');

        $this
            ->shouldThrow(new \Exception('Payment Method not supported'))
            ->duringRefund($boost);
    }
}
