<?php

namespace Spec\Minds\Core\Subscriptions;

use Minds\Core\Subscriptions\Manager;
use Minds\Core\Subscriptions\Repository;
use Minds\Core\Subscriptions\Subscription;
use Minds\Core\Subscriptions\Delegates;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    private $repository;
    private $copyToElasticSearchDelegate;
    private $sendNotificationDelegate;
    private $cacheDelegate;
    private $eventsDelegate;
    private $feedsDelegate;


    function let(
        Repository $repository,
        Delegates\CopyToElasticSearchDelegate $copyToElasticSearchDelegate = null,
        Delegates\SendNotificationDelegate $sendNotificationDelegate = null,
        Delegates\CacheDelegate $cacheDelegate = null,
        Delegates\EventsDelegate $eventsDelegate = null,
        Delegates\FeedsDelegate $feedsDelegate = null
    )
    {
        $this->beConstructedWith(
            $repository,
            $copyToElasticSearchDelegate,
            $sendNotificationDelegate,
            $cacheDelegate,
            $eventsDelegate,
            $feedsDelegate,
            true
        );
        $this->repository = $repository;
        $this->copyToElasticSearchDelegate = $copyToElasticSearchDelegate;
        $this->sendNotificationDelegate = $sendNotificationDelegate;
        $this->cacheDelegate = $cacheDelegate;
        $this->eventsDelegate = $eventsDelegate;
        $this->feedsDelegate = $feedsDelegate;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    // NOT IMPLEMENTED
    /*function it_should_say_not_subscribed()
    {
        $this->repository->get(Argument::that(function($subscription) {

        });

        $this->isSubscribed()
            ->shouldBe(false);
    }

    function it_should_say_subscribed()
    {
        $this->isSubscribed()
            ->shouldBe(true);
    }*/

    function it_should_subscribe()
    {
        // Confusing.. but this is the returned subscription
        // post repository
        $subscription = new Subscription;
        $subscription->setActive(true);

        $this->repository->add(Argument::that(function($sub) {
            return $sub->getSubscriberGuid() == 123
                && $sub->getPublisherGuid() == 456;
            }))
            ->shouldBeCalled()
            ->willReturn($subscription);
        
        $publisher = (new User)->set('guid', 456);
        $this->setSubscriber((new User)->set('guid', 123));

        // Call the send notification delegate
        $this->sendNotificationDelegate->send($subscription)
            ->shouldBeCalled();

        // Call the events delegate
        $this->eventsDelegate->trigger($subscription)
            ->shouldBeCalled();

        // Call the feeds delegate
        $this->feedsDelegate->copy($subscription)
            ->shouldBeCalled();

        // Call the es delegate
        $this->copyToElasticSearchDelegate->copy($subscription)
            ->shouldBeCalled();

        // Call the cache delegate
        $this->cacheDelegate->cache($subscription)
            ->shouldBeCalled();

        $newSubscription = $this->subscribe($publisher);
        $newSubscription->isActive()
            ->shouldBe(true);
    }

    function it_should_unsubscribe()
    {
        // Confusing.. but this is the returned subscription
        // post repository
        $subscription = new Subscription;
        $subscription->setActive(false);

        $this->repository->delete(Argument::that(function($sub) {
            return $sub->getSubscriberGuid() == 123
                && $sub->getPublisherGuid() == 456;
            }))
            ->shouldBeCalled()
            ->willReturn($subscription);
        
        $publisher = (new User)->set('guid', 456);
        $this->setSubscriber((new User)->set('guid', 123));

        // Call the events delegate
        $this->eventsDelegate->trigger($subscription)
            ->shouldBeCalled();

        // Call the feeds delegate
        $this->feedsDelegate->remove($subscription)
            ->shouldBeCalled();

        // Call the es delegate
        $this->copyToElasticSearchDelegate->remove($subscription)
            ->shouldBeCalled();

        // Call the cache delegate
        $this->cacheDelegate->cache($subscription)
            ->shouldBeCalled();
    
        $newSubscription = $this->unSubscribe($publisher);
        $newSubscription->isActive()
            ->shouldBe(false);
    }

}
