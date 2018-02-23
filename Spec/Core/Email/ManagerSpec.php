<?php

namespace Spec\Minds\Core\Email;

use Minds\Core\Email\Repository;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Manager');
    }

    function it_should_get_subscribers(Repository $repository)
    {
        $this->beConstructedWith($repository);

        $opts = [
            'campaign' => 'when',
            'topic' => 'boost_completed',
            'value' => true,
            'limit' => 2000,
        ];

        $user1 = new User();
        $user1->guid = '123';
        $user1->username = 'user1';
        $user2 = new User();
        $user1->guid = '456';
        $user1->username = 'user2';

        $repository->getList(Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn([
                'data' => [
                    $user1->guid,
                    $user2->guid
                ],
                'token' => '120123iasjdojqwoeij'
            ]);

        $this->getSubscribers($opts)->shouldBeArray();

    }

    function it_should_unsubscribe_a_user_from_a_campaign(Repository $repository)
    {
        $this->beConstructedWith($repository);

        $user = new User();
        $user->guid = '123';
        $user->username = 'user1';
        $campaign = 'when';
        $topic = 'boost_received';

        $repository->add(Argument::type('Minds\Core\Email\EmailSubscription'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->unsubscribe($user, $campaign, $topic)->shouldReturn(true);

    }
}
