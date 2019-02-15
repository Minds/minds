<?php
/**
 * Trigger events
 */
namespace Minds\Core\Subscriptions\Delegates;

use Minds\Core\Di\Di;

class EventsDelegate 
{

    /** @var EventsDispatcher $eventsDispatcher */
    private $eventsDispatcher;

    public function __construct($eventsDispatcher = null)
    {
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * Trigger an event 
     * @param Subscription $subscrition
     * @return void
     */
    public function trigger($subscription)
    {
        $this->eventsDispatcher->trigger($subscription->isActive() ? 'subscribe' : 'unsubscribe', 'all', [
            'user_guid' => $subscription->getSubscriberGuid(),
            'to_guid' => $subscription->getPublisherGuid(),
            'subscription' => $subscription,
        ]);
    }

}
