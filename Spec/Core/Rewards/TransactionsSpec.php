<?php

namespace Spec\Minds\Core\Rewards;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Rewards\Repository;
use Minds\Core\Rewards\Balance;
use Minds\Entities\User;

class TransactionsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Transactions');
    }

    function it_should_create_a_rewards_transaction(Repository $repo, Balance $balance)
    {
        $this->beConstructedWith($repo, $balance);

        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $balance->setUser($user)->willReturn($balance);
        $balance->get()->willReturn(10);

        $repo->add(Argument::that(function ($reward) use ($user) {
            return $reward->getUser() == $user
                && $reward->getAmount() == 5
                && $reward->getType() == 'spec';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->create()->shouldReturn('reward-tx:123:spec:' . (time() * 1000));
    }

    function it_should_not_create_a_rewards_transaction_if_insufficient_balance(Repository $repo, Balance $balance)
    {
        $this->beConstructedWith($repo, $balance);

        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(15)
            ->setType('spec');

        $balance->setUser($user)->willReturn($balance);
        $balance->get()->willReturn(10);

        $this->shouldThrow('\Exception')->duringCreate();
    }

    function it_should_throw_exception_if_save_fails(Repository $repo, Balance $balance)
    {
        $this->beConstructedWith($repo, $balance);

        $user = new User;
        $user->guid = 123;
        $this->setUser($user)
            ->setAmount(5)
            ->setType('spec');

        $balance->setUser($user)->willReturn($balance);
        $balance->get()->willReturn(10);

        $repo->add(Argument::that(function ($reward) use ($user) {
            return $reward->getUser() == $user
                && $reward->getAmount() == 5
                && $reward->getType() == 'spec';
            }))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->shouldThrow('\Exception')->duringCreate();
    }

}
