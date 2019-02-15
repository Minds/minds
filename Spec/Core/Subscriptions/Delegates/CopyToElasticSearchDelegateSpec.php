<?php

namespace Spec\Minds\Core\Subscriptions\Delegates;

use Minds\Core\Subscriptions\Delegates\CopyToElasticSearchDelegate;
use Minds\Core\Subscriptions\Subscription;
use Minds\Core\Data\ElasticSearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CopyToElasticSearchDelegateSpec extends ObjectBehavior
{

    private $es;

    function let(Client $es)
    {
        $this->beConstructedWith($es);
        $this->es = $es;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CopyToElasticSearchDelegate::class);
    }

    function it_should_copy_to_elasticsearch()
    {
        $subscription = new Subscription();
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456)
            ->setActive(true);

        $this->es->request(Argument::that(function($prepared) {
            $query = $prepared->build();
            return $query['body']['script']['params']['guid'] == 456
                && $query['id'] == 123;
        }))
            ->shouldBeCalled();

        $this->copy($subscription);
    }

    function it_should_remove_from_elasticsearch()
    {
        $subscription = new Subscription();
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456)
            ->setActive(true);

        $this->es->request(Argument::that(function($prepared) {
            $query = $prepared->build();
            return $query['body']['script']['params']['guid'] == 456
                && $query['id'] == 123;
        }))
            ->shouldBeCalled();

        $this->remove($subscription);
    }

}
