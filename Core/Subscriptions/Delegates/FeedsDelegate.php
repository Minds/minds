<?php
/**
 * Trigger events
 */
namespace Minds\Core\Subscriptions\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Data\Call;

class FeedsDelegate 
{
    /** @var Call $feed */
    private $feed;

    public function __construct($feed = null)
    {
        $this->feed = $feed ?: new Call('entities_by_time');
    }

    /**
     * Copy posts 
     * @param Subscription $subscrition
     * @return void
     */
    public function copy($subscription)
    {
        if ($subscription->getPublisherGuid() == '100000000000000519') {
            return;
        }

        // Sync copy of first 12 activities, if the user is not Minds
        $feed = $this->feed->getRow("activity:user:{$subscription->getPublisherGuid()}", [ 'limit' => 12 ]);
        if ($feed) {
            $this->feed->insert("activity:network:{$subscription->getSubscriberGuid()}", $feed);
        }
    }

    /**
     * Copy posts 
     * @param Subscription $subscrition
     * @return void
     */
    public function remove($subscription)
    {
        if ($subscription->getPublisherGuid() == '100000000000000519') {
            return;
        }

        // Sync copy of first 12 activities, if the user is not Minds
        $feed = $this->feed->getRow("activity:user:{$subscription->getPublisherGuid()}", [ 'limit' => 100 ]);
        if ($feed) {
            $this->feed->removeAttributes("activity:network:{$subscription->getSubscriberGuid()}", array_keys($feed));
        }
    } 

}
