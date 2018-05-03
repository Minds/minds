<?php

namespace Spec\Minds\Core\Payments\Subscriptions;

use Minds\Core\Di\Di;
use Minds\Core\Payments\Subscriptions\Manager;
use Minds\Core\Payments\Subscriptions\Repository;
use Minds\Core\Payments\Subscriptions\Subscription;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SubscriptionsIteratorSpec extends ObjectBehavior
{
    protected $repository;

    function let(
        Repository $repository
    )
    {
        $this->repository = $repository;

        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\Subscriptions\SubscriptionsIterator');
    }

    function it_should_get()
    {
        $timestamp = strtotime('2000-01-01T12:00:00+00:00');
        $subscriptions = [ 
            (new Subscription())
                ->setId('1'),
            (new Subscription())
                ->setId('2'),
        ];

        $this->setFrom($timestamp)
            ->setPlanId('spec')
            ->setPaymentMethod('tokens');

        $this->repository->getList([
            'plan_id' => 'spec',
            'payment_method' => 'tokens',
            'limit' => 2000,
            'status' => 'active',
            'next_billing' => $timestamp
        ])
            ->shouldBeCalled()
            ->willReturn($subscriptions);

        $this->rewind();

        $this->current()
            ->shouldReturn($subscriptions[0]);

        $this->next();

        $this->current()
            ->shouldReturn($subscriptions[1]);
    }

}
