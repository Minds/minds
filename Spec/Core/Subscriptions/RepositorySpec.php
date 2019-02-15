<?php

namespace Spec\Minds\Core\Subscriptions;

use Minds\Core\Subscriptions\Repository;
use Minds\Core\Subscriptions\Subscription;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{

    private $client;

    function let(Client $client)
    {
        $this->beConstructedWith($client);
        $this->client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_a_subscription()
    {
        $this->client->batchRequest(Argument::that(function($requests) {
            return $requests[0]['values'][0] == 123
                && $requests[0]['values'][1] == 456
                && $requests[1]['values'][0] == 456
                && $requests[1]['values'][1] == 123;
        }), 1)
            ->shouldBeCalled()
            ->willReturn(true);

        $subscription = new Subscription();
        $subscription->setSubscriberGuid(123);
        $subscription->setPublisherGuid(456);

        $newSubscription = $this->add($subscription);
        $newSubscription->isActive()
            ->shouldBe(true);
    }

    function it_should_delete_a_subscription()
    {
        $this->client->batchRequest(Argument::that(function($requests) {
            return $requests[0]['values'][0] == 123
                && $requests[0]['values'][1] == 456
                && $requests[1]['values'][0] == 456
                && $requests[1]['values'][1] == 123;
        }), 1)
            ->shouldBeCalled()
            ->willReturn(true);

        $subscription = new Subscription();
        $subscription->setSubscriberGuid(123);
        $subscription->setPublisherGuid(456);

        $newSubscription = $this->delete($subscription);
        $newSubscription->isActive()
            ->shouldBe(false);
    }

}
