<?php

namespace Minds\Core\Email\Batches;

use Minds\Core\Di\Di;
use Minds\Core\Email\Campaigns\WithActivity;
use Minds\Core\Email\EmailSubscribersIterator;
use Minds\Core\Entities;

class Activity implements EmailBatchInterface
{
    protected $period;
    protected $offset = '';

    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = isset($offset) ? $offset : '';
        return $this;
    }

    public function run()
    {
        $trendingPosts = $this->getTrendingActivities();

        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('with')
            ->setTopic('top_posts')
            //->setValue($this->period) //TODO: add back in later
            ->setOffset($this->offset)
            ->getSubscribers();
        
        foreach ($iterator as $user) {
            $campaign = new WithActivity();
            $campaign->setUser($user)
                ->setPosts($trendingPosts)
                ->send();
        }
    }

    /**
     * @return array
     */
    private function getTrendingActivities()
    {
        $result = Di::_()->get('Trending\Repository')->getList([
            'type' => 'newsfeed',
            'limit' => 12
        ]);
        ksort($result['guids']);
        $options['guids'] = $result['guids'];

        $activities = Entities::get(array_merge([
            'type' => 'activity'
        ], $options));

        $activities = array_filter($activities, function ($activity) {
            if ($activity->paywall) {
                return false;
            }

            if ($activity->remind_object && $activity->remind_object['paywall']) {
                return false;
            }

            return true;
        });

        return $activities;
    }
}
