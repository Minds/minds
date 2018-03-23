<?php

namespace Minds\Core\Email\Batches;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Email\Campaigns\WithActivity;
use Minds\Core\Email\EmailSubscribersIterator;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Trending\Repository;

class Activity implements EmailBatchInterface
{
    /** @var Client */
    protected $db;
    /** @var Repository */
    protected $repository;
    /** @var EntitiesBuilder */
    protected $builder;

    protected $period;
    protected $offset = '';

    public function __construct($db = null, $trendingRepository = null, $builder = null)
    {
        $this->db = $db ?: new Client();
        $this->repository = $trendingRepository ?: Di::_()->get('Trending\Repository');
        $this->builder = $builder ?: Di::_()->get('EntitiesBuilder');
    }

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

        $subscribers_map = $this->buildSubscribersMap($trendingPosts);

        if (!$trendingPosts || count($trendingPosts) === 0) {
            return;
        }

        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('with')
            ->setTopic('top_posts')
            //->setValue($this->period) //TODO: add back in later
            ->setOffset($this->offset)
            ->getSubscribers();

        foreach ($iterator as $user) {
            $filtered = array_filter($trendingPosts, function ($activity) use ($user, $subscribers_map) {
                return in_array($activity->owner_guid, $subscribers_map[$user->guid]);
            });
            if ($filtered && count($filtered) >= 1) {
                $campaign = new WithActivity();
                $campaign->setUser($user)
                    ->setPosts($filtered)
                    ->send();
            }
            echo "\n$user->guid...";
        }
    }

    /**
     * @return array
     */
    private function getTrendingActivities()
    {
        $result = $this->repository->getList([
            'type' => 'newsfeed',
            'limit' => 12
        ]);
        
        if (!$result || !$result['guids'] || count($result['guids']) === 0) {
            return [];
        }

        ksort($result['guids']);
        $options['guids'] = $result['guids'];

        $activities = $this->builder->get(array_merge([
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

    private function buildSubscribersMap($activities)
    {
        $subscribers_map = [];

        $owner_guids = array_unique(array_map(function ($activity) {
            return $activity->owner_guid;
        }, $activities));

        foreach ($owner_guids as $owner_guid) {

            $prepared = new Custom();
            $prepared->query("SELECT * from friendsof where key=?", [
                (string) $owner_guid
            ]);
            $rows = $this->db->request($prepared);
            while (true) {
                foreach ($this->db->request($prepared) as $row) {
                    $subscriber_guid = $row['column1'];
                    $subscribers_map[$subscriber_guid][] = $owner_guid;
                }
                if ($rows->isLastPage()) {
                    break;
                }
                $rows = $rows->nextPage();
            }
        }
        return $subscribers_map;
    }
}
