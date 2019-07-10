<?php

namespace Spec\Minds\Core\Email;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Email\Repository;
use Minds\Core\Email\CampaignLogs\Repository as CampaignLogsRepository;
use Minds\Entities\User;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\CampaignLogs\CampaignLog;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $campaignLogsRepository;

    function let(Repository $repository, CampaignLogsRepository $campaignLogsRepository) {
        $this->repository = $repository;
        $this->campaignLogsRepository = $campaignLogsRepository;
        $this->beConstructedWith($this->repository, $this->campaignLogsRepository);
 
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Email\Manager');
    }

    function it_should_get_subscribers()
    {
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

        $this->repository->getList(Argument::type('array'))
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

    function it_should_unsubscribe_a_user_from_a_campaign()
    {
        $user = new User();
        $user->guid = '123';
        $user->username = 'user1';

        $this->repository->delete(Argument::type('Minds\Core\Email\EmailSubscription'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->unsubscribe($user, [ 'when' ], [ 'boost_received' ])
            ->shouldReturn(true);

    }

    function it_should_unsubscribe_from_all_emails()
    {
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

        $this->repository->getList([
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

        $this->repository->delete($subscriptions[0])
            ->shouldBeCalled();

        $this->repository->delete($subscriptions[1])
            ->shouldBeCalled();
        
        $this->unsubscribe($user)
            ->shouldReturn(true);
    }

    function it_should_save_a_campaign_log() {
        $campaignLog = new CampaignLog();
        $this->campaignLogsRepository->add($campaignLog)->shouldBeCalled();
        $this->saveCampaignLog($campaignLog);
    }

    function it_should_get_campaign_logs() {
        $user = new User();
        $user->guid = '123';
        $options = [
            'receiver_guid' => $user->guid
        ]; 
        $this->campaignLogsRepository->getList($options)->shouldBeCalled();
        $this->getCampaignLogs($user);
    }

}
