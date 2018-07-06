<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Data\Locks\Redis as Locks;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Wallets\OffChain\Balance;
use Minds\Entities\User;

class TransactionsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Wallets\OffChain\Transactions');
    }

    function it_should_create_a_rewards_transaction(Repository $repo, Balance $balance, Locks $locks)
    {
        $this->beConstructedWith($repo, $balance, $locks);

        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $locks->setKey(Argument::any())
            ->shouldBeCalled()
            ->willReturn($locks);
        $locks->setTTL(120)
            ->shouldBeCalled()
            ->willReturn($locks);
        $locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);
        $locks->lock()
            ->shouldBeCalled()
            ->willReturn(null);
        $locks->unlock()
            ->shouldBeCalled();

        $balance->setUser($user)->willReturn($balance);
        $balance->get()->willReturn(10);

        $guid = null;

        $repo->add(Argument::that(function ($transaction) use ($user, &$guid) {
            $guid = $transaction->getTx();
            return $transaction->getUserGuid() == $user->guid
                && $transaction->getWalletAddress() == 'offchain'
                && $transaction->getAmount() == 5
                && $transaction->getContract() == 'offchain:spec'
                && $transaction->isCompleted() == true
                && $transaction->isFailed() == false;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $transaction = $this->create();
        $transaction->getTx()->shouldBe($guid);
    }

    function it_should_not_create_a_rewards_transaction_if_insufficient_balance(Repository $repo, Balance $balance, Locks $locks)
    {
        $this->beConstructedWith($repo, $balance, $locks);

        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $locks->setKey(Argument::any())
            ->shouldBeCalled()
            ->willReturn($locks);
        $locks->setTTL(120)
            ->shouldBeCalled()
            ->willReturn($locks);
        $locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);
        $locks->lock()
            ->shouldBeCalled()
            ->willReturn(null);

        $balance->setUser($user)->willReturn($balance);
        $balance->get()->willReturn(10);

        $this->shouldThrow('\Exception')->duringCreate();
    }

    function it_should_throw_exception_if_save_fails(Repository $repo, Balance $balance, Locks $locks)
    {
        $this->beConstructedWith($repo, $balance, $locks);

        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $locks->setKey(Argument::that(function ($key) use ($user) {
            return $key === "balance:{$user->guid}";
        }))
            ->shouldBeCalled()
            ->willReturn($locks);
        $locks->setTTL(120)
            ->shouldBeCalled()
            ->willReturn($locks);
        $locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);
        $locks->lock()
            ->shouldBeCalled()
            ->willReturn(null);

        $balance->setUser($user)->willReturn($balance);
        $balance->get()->willReturn(10);
        //$balance->add()->lt(0)->willReturn(10);

        $repo->add(Argument::that(function ($transaction) use ($user) {
            return $transaction->getUserGuid() == $user->guid
                && $transaction->getAmount() == 5
                && $transaction->getContract() == 'offchain:spec';
            }))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->shouldThrow('\Exception')->duringCreate();
    }

    function it_should_throw_exception_if_locked(Repository $repo, Balance $balance, Locks $locks)
    {
        $this->beConstructedWith($repo, $balance, $locks);

        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $locks->setKey(Argument::that(function ($key) use ($user) {
            return $key === "balance:{$user->guid}";
        }))
            ->shouldBeCalled()
            ->willReturn($locks);
        $locks->isLocked()
            ->shouldBeCalled()
            ->willReturn(true);

        $balance->setUser($user)->willReturn($balance);
        $balance->get()->willReturn(10);

        $this->shouldThrow('\Exception')->duringCreate();
    }

}
