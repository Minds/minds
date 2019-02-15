<?php

namespace Spec\Minds\Core\Subscriptions\Delegates;

use Minds\Core\Subscriptions\Delegates\CacheDelegate;
use Minds\Core\Subscriptions\Subscription;
use Minds\Core\Data\cache\abstractCacher as Cache;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CacheDelegateSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(CacheDelegate::class);
    }

    function it_should_set_the_cache_for_an_active_subscription(Cache $cache)
    {
        $this->beConstructedWith($cache);
        $cache->set('123:isSubscribed:456', true)
            ->shouldBeCalled();
        $cache->set('456:isSubscriber:123', true)
            ->shouldBeCalled();
        $cache->destroy('friends:123')
            ->shouldBeCalled();
        $cache->destroy('friendsof:456')
            ->shouldBeCalled();

        $subscription = new Subscription();
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456)
            ->setActive(true);

        $this->cache($subscription);
    }

    function it_should_set_the_cache_for_an_inactive_subscription(Cache $cache)
    {
        $this->beConstructedWith($cache);
        $cache->set('123:isSubscribed:456', false)
            ->shouldBeCalled();
        $cache->set('456:isSubscriber:123', false)
            ->shouldBeCalled();
        $cache->destroy('friends:123')
            ->shouldBeCalled();
        $cache->destroy('friendsof:456')
            ->shouldBeCalled();

        $subscription = new Subscription();
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456)
            ->setActive(false);

        $this->cache($subscription);
    }

}
