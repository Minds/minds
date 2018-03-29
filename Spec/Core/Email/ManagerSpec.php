<?php

namespace Spec\Minds\Core\Email;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Email\Repository;
use Minds\Entities\User;
use Minds\Core\Email\EmailSubscription;

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

        $repository->delete(Argument::type('Minds\Core\Email\EmailSubscription'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->unsubscribe($user, [ 'when' ], [ 'boost_received' ])
            ->shouldReturn(true);

    }

    function it_should_unsubscribe_from_all_emails(Repository $repository)
    {
        $this->beConstructedWith($repository);

        $user = new User();
        $user->guid = '123';

        $subscriptions = [
            (new EmailSubscription)
                ->setUserGuid($user->guid)
                ->setCampaign('when')
                ->setTopic('unread_notifications'),
            (new EmailSubscription)
                ->setUserGuid($user->guid)
                ->setCampaign('with')
                ->setTopic('top_posts'),
        ];

        $repository->getList([
            'campaigns' => [ 'when', 'with', 'global' ],
            'topics' => [ 
                'unread_notifications',
                'wire_received',
                'boost_completed',
                'top_posts',
                'channel_improvement_tips',
                'posts_missed_since_login',
                'new_channels',
                'minds_news',
                'minds_tips',
                'exclusive_promotions',
            ],
            'user_guid' => $user->guid,
        ])
            ->shouldBeCalled()
            ->willReturn($subscriptions);

        $repository->delete($subscriptions[0])
            ->shouldBeCalled();

        $repository->delete($subscriptions[1])
            ->shouldBeCalled();
        
        $this->unsubscribe($user)
            ->shouldReturn(true);
    }

}
