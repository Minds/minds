<?php


namespace Minds\Core\Email;


use Minds\Core\Di\Di;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Entities;
use Minds\Entities\User;
use Minds\Core\Email\Repository;
use Minds\Core\Email\CampaignLogs\Repository as CampaignLogsRepository;
use Minds\Core\Email\CampaignLogs\CampaignLog;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var CampaignLogsRepository */
    protected $campaignLogsRepository;


    public function __construct(Repository $repository = null, CampaignLogsRepository $campaignLogsRepository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Email\Repository');
        $this->campaignLogsRepository = $campaignLogsRepository ?: Di::_()->get('Email\CampaignLogs\Repository');
    }

    public function getSubscribers($options = [])
    {
        $options = array_merge([
            'campaign' => null,
            'topic' => null,
            'value' => false,
            'limit' => 2000,
            'offset' => ''
        ], $options);

        $result = $this->repository->getList($options);

        if (!$result || count($result['data'] === 0)) {
            return [];
        }

        $guids = array_map(function ($item) {
            return $item->getUserGuid();
        }, $result['data']);

        return [
            'users' => Entities::get(['guids' => $guids]),
            'token' => $result['token']
        ];
    }

    public function isSubscribed(EmailSubscription $subscription)
    {
        $result = $this->repository->getList([
            'user_guid' => $subscription->getUserGuid(),
            'campaign' => $subscription->getCampaign(),
            'topic' => $subscription->getTopic(),
            'value' => $subscription->getValue(),
        ]);

        return count($result['data']) > 0 && $result['data'][0]->getValue() !== '0' && $result['data'][0]->getValue() !== '';
    }

    /**
     * Unsubscribe from emails
     * @param User $user
     * @param array $campaigns
     * @param array $topics
     * @return bool
     */
    public function unsubscribe($user, $campaigns = [], $topics = [])
    {
        if (!$campaigns) {
            $campaigns = [ 'when', 'with', 'global' ];
        }

        if (!$topics) {
            $topics = [ 
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
            ];
        }

        //We can skip the read here
        if (count($campaigns) == 1 && count($topics) >= 1) {
            $subscriptions = [];
            foreach ($topics as $topic) {
                $subscriptions[] = (new EmailSubscription)
                    ->setUserGuid($user->guid)
                    ->setCampaign($campaigns[0])
                    ->setTopic($topic);
            }
        } else {
            $subscriptions = $this->repository->getList([
                'campaigns' => $campaigns,
                'topics' => $topics,
                'user_guid' => $user->guid,
            ]);
        }

        foreach ($subscriptions as $subscription) {
            $this->repository->delete($subscription);
        }

        return true;
    }

    /**
     * Saves a log when we send a user a campaign email
     * Used to select subsequent mailings and send different emails
     * @param CampaignLog $campaignLog the receiver, time and campaign class name
     * @return boolean the add result
     */
    public function saveCampaignLog(CampaignLog $campaignLog) {
        $this->campaignLogsRepository->add($campaignLog);
    }

    public function getCampaignLogs(User $receiver) {
        $options = [
            'receiver_guid' => $receiver->guid
        ];
        return $this->campaignLogsRepository->getList($options);
    }
}
