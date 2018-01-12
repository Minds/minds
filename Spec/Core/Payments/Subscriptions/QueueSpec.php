<?php

namespace Spec\Minds\Core\Payments\Subscriptions;

use Minds\Core\Di\Di;
use Minds\Core\Payments\Subscriptions\Manager;
use Minds\Core\Payments\Subscriptions\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QueueSpec extends ObjectBehavior
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
        $this->shouldHaveType('Minds\Core\Payments\Subscriptions\Queue');
    }

    function it_should_get()
    {
        $timestamp = strtotime('2000-01-01T12:00:00+00:00');
        $rows = [ true, true, true ];

        $this->repository->getList([
            'status' => 'active',
            'next_billing' => $timestamp
        ])
            ->shouldBeCalled()
            ->willReturn($rows);

        $this
            ->get($timestamp)
            ->shouldReturn($rows);
    }

    /*function it_should_get_using_a_date_time_object()
    {
        $timestamp = strtotime('2000-01-01T12:00:00+00:00');
        $rows = [ true, true, true ];

        $this->repository->select([
            'status' => 'active',
            'next_billing' => $timestamp
        ])
            ->shouldBeCalled()
            ->willReturn($rows);

        $this
            ->get(new \DateTime("@{$timestamp}"))
            ->shouldReturn($rows);
    }

    function it_should_get_an_empty_set()
    {
        $timestamp = strtotime('2000-01-01T12:00:00+00:00');

        $this->repository->select([
            'status' => 'active',
            'next_billing' => $timestamp
        ])
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->get($timestamp)
            ->shouldReturn([]);
    }

    function it_should_set_as_processed(
        Manager $manager
    )
    {
        Di::_()->bind('Payments\Subscriptions\Manager', function () use ($manager) {
            return $manager->getWrappedObject();
        });

        $recurring_subscription = [
            'type' => 'test',
            'payment_method' => 'specs',
            'entity_guid' => 4000,
            'user_guid' => 1000,
            'last_billing' => 10000000,
            'recurring' => 'daily'
        ];

        $manager->setType($recurring_subscription['type'])
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setPaymentMethod($recurring_subscription['payment_method'])
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setEntityGuid($recurring_subscription['entity_guid'])
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setUserGuid($recurring_subscription['user_guid'])
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->updateBilling($recurring_subscription['last_billing'], $recurring_subscription['recurring'])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->shouldNotThrow(\Exception::class)
            ->duringProcessed($recurring_subscription);
    }*/
}
