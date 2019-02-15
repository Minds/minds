<?php

namespace Spec\Minds\Core\Subscriptions\Delegates;

use Minds\Core\Subscriptions\Delegates\FeedsDelegate;
use Minds\Core\Subscriptions\Subscription;
use Minds\Core\Data\Call;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FeedsDelegateSpec extends ObjectBehavior
{

    private $feed;

    function let(Call $feed)
    {
        $this->beConstructedWith($feed);
        $this->feed = $feed;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FeedsDelegate::class);
    }

    function it_should_copy()
    {
        $this->feed->getRow('activity:user:456', [ 'limit' => 12 ])
            ->shouldBeCalled()
            ->willReturn([
                111 => 111,
                222 => 222,
                333 => 333,
            ]);

        $this->feed->insert('activity:network:123', [
            111 => 111,
            222 => 222,
            333 => 333,
        ])
            ->shouldBeCalled();

        $subscription = new Subscription();
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456);

        $this->copy($subscription);
    }

    function it_should_remove()
    {
        $this->feed->getRow('activity:user:456', [ 'limit' => 100 ])
            ->shouldBeCalled()
            ->willReturn([
                111 => 111,
                222 => 222,
                333 => 333,
            ]);

        $this->feed->removeAttributes('activity:network:123', [
            111,
            222,
            333,
        ])
            ->shouldBeCalled();
        
        $subscription = new Subscription();
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456);

        $this->remove($subscription);
    }

}
