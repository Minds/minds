<?php

namespace Minds\Core\Email\Batches;

use Minds\Core\Di\Di;
use Minds\Core\Analytics\Iterators\SignupsIterator;
use Minds\Core\Email\Campaigns\GlobalTips;
use Minds\Core\Email\EmailSubscribersIterator;
use Minds\Core\Email\EmailSubscription;

class RetentionTips implements EmailBatchInterface
{
    protected $offset;
    protected $period;

    public function __construct($manager = null)
    {
        $this->manager = $manager ?: Di::_()->get('Email\Manager');
    }

    /**
     * @param string $offset
     * @return RetentionTips
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * The retention period
     * @param int $period
     * @return RetentionTips
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    public function run()
    {
        $iterator = new SignupsIterator;
        $iterator->setPeriod($this->period);

        $i = 0;
        foreach ($iterator as $user) {
            $i++;
            echo "\n[$i]:$user->guid ";

            //check if the user is subscribed
            $subscription = (new EmailSubscription)
                ->setUserGuid($user->guid)
                ->setCampaign('global')
                ->setTopic('minds_tips');

            if (!$this->manager->isSubscribed($subscription)) {
                echo "... skipping";
                continue;
            }

            $campaign = new GlobalTips();
            $type = 'rewards';

            switch ($this->period) {
                case 1:
                    $type = 'rewards';
                    break;
                case 3:
                    $type = 'boost';
                    break;
                case 7:
                    $type = 'wire';
                    break;
                default:
                    throw new \Exception('Period not found or not set');
            }
            echo "... sending";
            
            $campaign
                ->setType($type)
                ->setUser($user)
                ->send();
        }
    }
}
