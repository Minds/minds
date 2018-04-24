<?php

namespace Spec\Minds\Core\Rewards;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Rewards\Contributions\Manager as ContributionsManager;
use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Entities\User;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Manager');
    }

    function it_should_sync_contributions_to_rewards(
        ContributionsManager $contributions,
        Transactions $transactions,
        Repository $txRepository
    )
    {
        $this->beConstructedWith($contributions, $transactions, $txRepository);

        $from = strtotime('midnight tomorrow -24 hours', time()) * 1000;
        $to = strtotime('midnight tomorrow', time()) * 1000;
        $user = new User;
        $user->guid = 123;

        $txRepository->getList([
            'user_guid' => 123,
            'wallet_address' => 'offchain',
            'timestamp' => [
                'gte' => $from,
                'lte' => $to,
                'eq' => null,
            ],
            'contract' => 'offchain:reward',
            ])
            ->shouldBeCalled()
            ->willReturn(null);

        $contributions
            ->setFrom($from)
            ->shouldBeCalled()
            ->willReturn($contributions);

        $contributions
            ->setTo($to)
            ->shouldBeCalled()
            ->willReturn($contributions);
        
        $contributions
            ->setUser($user)
            ->shouldBeCalled()
            ->willReturn($contributions);

        $contributions->getRewardsAmount()
            ->shouldBeCalled()
            ->willReturn(20);

        $txRepository->add(Argument::that(function($transaction) {
            return true;
        }))
            ->shouldBeCalled();

        $this->setUser($user)
            ->setFrom($from)
            ->setTo($to);

        $this->sync()->getAmount()->shouldBe(20);
        $this->sync()->getContract()->shouldBe('offchain:reward');
        $this->sync()->getTimestamp()->shouldBe(strtotime('-1 second', $to / 1000));
    }

    function it_should_not_allow_duplicate_rewards_to_be_sent(
        ContributionsManager $contributions,
        Transactions $transactions,
        Repository $txRepository
    )
    {
        $this->beConstructedWith($contributions, $transactions, $txRepository);

        $txRepository->getList([
            'user_guid' => 123,
            'wallet_address' => 'offchain',
            'timestamp' => [
                'gte' => time() * 1000,
                'lte' => time() * 1000,
                'eq' => null,
            ],
            'contract' => 'offchain:reward',
            ])
            ->shouldBeCalled()
            ->willReturn([(new Transaction)]);

        $user = new User;
        $user->guid = 123;   
        $this->setUser($user)
            ->setFrom(time() * 1000)
            ->setTo(time() * 1000);

        $this->shouldThrow('\Exception')->duringSync();
    }

}
