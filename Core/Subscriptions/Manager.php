<?php
/**
 * Subscriptions manager
 */
namespace Minds\Core\Subscriptions;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var User $subscriber */
    private $subscriber;

    /** @var CopyToElasticSearchDelegate $copyToElasticSearchDelegate */
    private $copyToElasticSearchDelegate;

    /** @var SendNotificationDelegate $sendNotificationDelegate */
    private $sendNotificationDelegate;

    /** @var $cacheDelegate */
    private $cacheDelegate;

    /** @var EventsDelegate $eventsDelegate */
    private $eventsDelegate;

    /** @var FeedsDelegate $feedsDelegate */
    private $feedsDelegate;

    public function __construct(
        $repository = null,
        $copyToElasticSearchDelegate = null,
        $sendNotificationDelegate = null,
        $cacheDelegate = null,
        $eventsDelegate = null,
        $feedsDelegate = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->copyToElasticSearchDelegate = $copyToElasticSearchDelegate ?: new Delegates\CopyToElasticSearchDelegate;
        $this->sendNotificationDelegate = $sendNotificationDelegate ?: new Delegates\SendNotificationDelegate;
        $this->cacheDelegate = $cacheDelegate ?: new Delegates\CacheDelegate;
        $this->eventsDelegate = $eventsDelegate ?: new Delegates\EventsDelegate;
        $this->feedsDelegate = $feedsDelegate ?: new Delegates\FeedsDelegate;
    }

    public function setSubscriber($user)
    {
        $this->subscriber = $user;
        return $this;
    }

    /**
     * Is the subscriber subscribed to the publisher
     * @param User $publisher
     * @return bool
     */
    public function isSubscribed($publisher)
    {
        $subscription = new Subscription();
        $subscription->setSubscriberGuid($this->subscriber->getGuid())
            ->setPublisherGuid($publisher->getGuid());
        return $this->repository->get($subscription);
    }

    /**
     * Subscribe to a publisher
     * @param User $publisher
     * @return Subscription
     */
    public function subscribe($publisher)
    {
        $subscription = new Subscription();
        $subscription->setSubscriberGuid($this->subscriber->getGuid())
            ->setPublisherGuid($publisher->getGuid())
            ->setActive(true);

        $subscription = $this->repository->add($subscription);

        $this->sendNotificationDelegate->send($subscription);
        $this->eventsDelegate->trigger($subscription);
        $this->feedsDelegate->copy($subscription);
        $this->copyToElasticSearchDelegate->copy($subscription);
        $this->cacheDelegate->cache($subscription);

        return $subscription;
    }

    /**
     * UnSubscribe to a publisher
     * @param User $publisher
     * @return Subscription
     */
    public function unSubscribe($publisher)
    {
        $subscription = new Subscription();
        $subscription->setSubscriberGuid($this->subscriber->getGuid())
            ->setPublisherGuid($publisher->getGuid())
            ->setActive(false);

        $subscription = $this->repository->delete($subscription);

        $this->eventsDelegate->trigger($subscription);
        $this->feedsDelegate->remove($subscription);
        $this->copyToElasticSearchDelegate->remove($subscription);
        $this->cacheDelegate->cache($subscription);

        return $subscription;
    } 

}
