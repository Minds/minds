<?php

namespace Spec\Minds\Core\Rewards;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Rewards\Contributions\Manager as ContributionsManager;
use Minds\Core\Rewards\Repository;
use Minds\Core\Rewards\Reward;
use Minds\Entities\User;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Manager');
    }

    function it_should_sync_contributions_to_rewards(ContributionsManager $contributions, Repository $repository)
    {
        $this->beConstructedWith($contributions, $repository);

        $timestamp = time() * 1000;
        $user = new User;
        $user->guid = 123;

        $repository->getList([
                'timestamp' => $timestamp,
                'type' => 'contribution',
                'user_guid' => 123
            ])
            ->shouldBeCalled()
            ->willReturn(null);

        $contributions
            ->setFrom($timestamp)
            ->shouldBeCalled()
            ->willReturn($contributions);

        $contributions
            ->setTo(time() * 1000)
            ->shouldBeCalled()
            ->willReturn($contributions);
        
        $contributions
            ->setUser($user)
            ->shouldBeCalled()
            ->willReturn($contributions);

        $contributions->getRewardsAmount()
            ->shouldBeCalled()
            ->willReturn(20);
        
        $reward = (new Reward)
            ->setType('contribution')
            ->setTimestamp($timestamp)
            ->setUser($user)
            ->setAmount(20);

        $repository->add($reward)
            ->shouldBeCalled();

        $this->setUser($user)
            ->setFrom($timestamp)
            ->setTo(time() * 1000);

        $this->sync()->getAmount()->shouldBe(20);
        $this->sync()->getType()->shouldBe('contribution');
        $this->sync()->getTimestamp()->shouldBe($timestamp);
    }

    function it_should_not_allow_duplicate_rewards_to_be_sent(ContributionsManager $contributions, Repository $repository)
    {
        $this->beConstructedWith($contributions, $repository);

        $repository->getList(Argument::any())
            ->shouldBeCalled()
            ->willReturn([
                (new Reward)
                ->setType('contribution')
                ->setTimestamp(time())
                ->setAmount(20)
            ]);

        $user = new User;
        $user->guid = 123;   
        $this->setUser($user);

        $this->shouldThrow('\Exception')->duringSync();
    }

}
