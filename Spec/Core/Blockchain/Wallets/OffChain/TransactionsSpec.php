<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Wallets\OffChain\Balance;
use Minds\Core\Data\Locks\LockFailedException;
use Minds\Core\Data\Locks\Redis as Locks;
use Minds\Core\GuidBuilder;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionsSpec extends ObjectBehavior
{
    /** @var Repository */
    private $repo;
    /** @var Balance */
    private $balance;
    /** @var Locks */
    private $locks;
    /** @var GuidBuilder */
    private $guid;

    function let(Repository $repo, Balance $balance, Locks $locks, GuidBuilder $guid)
    {
        $this->repo = $repo;
        $this->balance = $balance;
        $this->locks = $locks;
        $this->guid = $guid;

        $this->beConstructedWith($repo, $balance, $locks, $guid);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Wallets\OffChain\Transactions');
    }

    function it_should_create_a_rewards_transaction()
    {
        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $this->locks->setKey(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->setTTL(120)
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);
        $this->locks->lock()
            ->shouldBeCalled()
            ->willReturn(null);
        $this->locks->unlock()
            ->shouldBeCalled();

        $this->balance->setUser($user)->willReturn($this->balance);
        $this->balance->get()->willReturn(10);

        $this->guid->build()
            ->shouldBeCalled()
            ->willReturn('123');

        $this->repo->add(Argument::that(function ($transaction) use ($user) {
            return $transaction->getTx() == 'oc:123'
                && $transaction->getUserGuid() == $user->guid
                && $transaction->getWalletAddress() == 'offchain'
                && $transaction->getAmount() == 5
                && $transaction->getContract() == 'offchain:spec'
                && $transaction->isCompleted() == true
                && $transaction->isFailed() == false;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->create();
    }

    function it_should_create_a_rewards_transaction_but_fail_to_lock()
    {
        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $this->locks->setKey(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->setTTL(120)
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);
        $this->locks->unlock()
            ->shouldBeCalled();
        $this->locks->lock()
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->balance->setUser($user)->willReturn($this->balance);
        $this->balance->get()->willReturn(10);

        $this->guid->build()
            ->shouldBeCalled()
            ->willReturn('123');

        $this->repo->add(Argument::that(function ($transaction) use ($user) {
            return $transaction->getTx() == 'oc:123'
                && $transaction->getUserGuid() == $user->guid
                && $transaction->getWalletAddress() == 'offchain'
                && $transaction->getAmount() == 5
                && $transaction->getContract() == 'offchain:spec'
                && $transaction->isCompleted() == true
                && $transaction->isFailed() == false;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->create();
    }

    function it_should_not_create_a_rewards_transaction_if_insufficient_balance()
    {
        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $this->locks->setKey(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->setTTL(120)
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);
        $this->locks->lock()
            ->shouldBeCalled()
            ->willReturn(null);

        $this->balance->setUser($user)->willReturn($this->balance);
        $this->balance->get()->willReturn(-55);

        $this->shouldThrow(new \Exception('Not enough funds'))->duringCreate();
    }

    function it_should_throw_exception_if_save_fails()
    {
        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $this->locks->setKey(Argument::that(function ($key) use ($user) {
            return $key === "balance:{$user->guid}";
        }))
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->setTTL(120)
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);
        $this->locks->lock()
            ->shouldBeCalled()
            ->willReturn(null);

        $this->balance->setUser($user)->willReturn($this->balance);
        $this->balance->get()->willReturn(10);

        $this->guid->build()
            ->shouldBeCalled()
            ->willReturn('123');

        $this->repo->add(Argument::that(function ($transaction) use ($user) {
            return $transaction->getUserGuid() == $user->guid
                && $transaction->getAmount() == 5
                && $transaction->getContract() == 'offchain:spec';
        }))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->shouldThrow(new \Exception('Could not add transaction'))->duringCreate();
    }

    function it_should_throw_exception_if_locked()
    {
        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $this->locks->setKey(Argument::that(function ($key) use ($user) {
            return $key === "balance:{$user->guid}";
        }))
            ->shouldBeCalled()
            ->willReturn($this->locks);
        $this->locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->balance->setUser($user)->willReturn($this->balance);
        $this->balance->get()->willReturn(10);

        $this->shouldThrow(new LockFailedException())->duringCreate();
    }

    function it_should_convert_a_value_to_wei()
    {
        $this->toWei(10)->shouldReturn('10000000000000000000');
    }

}
