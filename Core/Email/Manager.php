<?php


namespace Minds\Core\Email;


use Minds\Core\Di\Di;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Entities;

class Manager
{
    /** @var Repository */
    protected $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Email\Repository');
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

    public function unsubscribe($user, $campaign, $topic)
    {
        return $this->repository->add(new EmailSubscription(
            [
                'user_guid' => $user->guid,
                'campaign' => $campaign,
                'topic' => $topic,
                'value' => false
            ]));
    }
}