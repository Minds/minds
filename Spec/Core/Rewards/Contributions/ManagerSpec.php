<?php

namespace Spec\Minds\Core\Rewards\Contributions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Rewards\Contributions\Repository;
use Minds\Core\Rewards\Contributions\Contribution;
use Minds\Core\Rewards\Contributions\Sums;
use Minds\Core\Analytics\Manager;
use Minds\Entities\User;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Contributions\Manager');
    }

    function it_should_sync_users_rewards_from_their_analytics(Repository $repository, Manager $analytics)
    {
        $this->beConstructedWith($analytics, $repository);

        $user = new User();
        $user->guid = 123;
        $this->setUser($user);
        

        $analytics->setUser(Argument::any())->shouldBeCalled()->willReturn($analytics);
        $analytics->setFrom(strtotime('-7 days') * 1000)->shouldBeCalled()->willReturn($analytics);
        $analytics->setTo(time() * 1000)->shouldBeCalled()->willReturn($analytics);
        $analytics->setInterval('day')->shouldBeCalled()->willReturn($analytics);

        $dayAgo = (strtotime('-1 day') * 1000);
        $twoDaysAgo = (strtotime('-2 days') * 1000);
        $threeDaysAgo = (strtotime('-3 days') * 1000);

        $analytics->getCounts()->shouldBeCalled()->willReturn([
            $dayAgo => [
                'votes' => 24,
                'comments' => 10,
                'reminds' => 4
            ],
            $twoDaysAgo => [
                'votes' => 40,
                'comments' => 20,
                'reminds' => 1
            ],
            $threeDaysAgo => [
                'votes' => 2,
                'comments' => 1,
                'reminds' => 40
            ]
        ]);

        $contributions = [
            (new Contribution)->setMetric('votes')->setUser($user)->setTimestamp($dayAgo)->setAmount(24),
            (new Contribution)->setMetric('comments')->setUser($user)->setTimestamp($dayAgo)->setAmount(20),
            (new Contribution)->setMetric('reminds')->setUser($user)->setTimestamp($dayAgo)->setAmount(16),

            (new Contribution)->setMetric('votes')->setUser($user)->setTimestamp($twoDaysAgo)->setAmount(40),
            (new Contribution)->setMetric('comments')->setUser($user)->setTimestamp($twoDaysAgo)->setAmount(40),
            (new Contribution)->setMetric('reminds')->setUser($user)->setTimestamp($twoDaysAgo)->setAmount(4),

            (new Contribution)->setMetric('votes')->setUser($user)->setTimestamp($threeDaysAgo)->setAmount(2),
            (new Contribution)->setMetric('comments')->setUser($user)->setTimestamp($threeDaysAgo)->setAmount(2),
            (new Contribution)->setMetric('reminds')->setUser($user)->setTimestamp($threeDaysAgo)->setAmount(160)
        ];

        $repository->add($contributions)->shouldBeCalled();

        $this->sync();
    }

    function it_should_return_the_value_rewards_to_issue(Sums $sums)
    {
        $this->beConstructedWith(null, null, $sums);

        $sums->setTimestamp(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn($sums);
    
        $sums->setUser(Argument::any())
            ->shouldBeCalled()
            ->willReturn($sums);

        $sums->getAmount()
            ->shouldBeCalled()
            ->willReturn(1, 10);

        $supply = (100000000 * 10 ** 18 / 4) / 365;
        
        $this->getRewardsAmount()->shouldReturn($supply / 10);
    }

}
