<?php

namespace Spec\Minds\Core\Payments\Subscriptions;

use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Payments\Subscriptions\Subscription;
use Minds\Entities\User;
use Minds\Entities\Entity;

class RepositorySpec extends ObjectBehavior
{
    protected $cql;

    function let(
        Client $cql
    )
    {
        $this->cql = $cql;

        $this->beConstructedWith($cql);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\Subscriptions\Repository');
    }

    function it_should_getList()
    {
        $rows = [ 
            [
                'plan_id' => 'a',
                'payment_method' => 'spec',
                'entity_guid' => 0,
                'user_guid' => 123,
                'subscription_id' => 'abc1',
                'amount' => new \Cassandra\Decimal(12),
                'interval' => 'monthly',
                'last_billing' => new \Cassandra\Timestamp(time()),
                'next_billing' => new \Cassandra\Timestamp(time()),
                'status' => 'active'
            ],
            [
                'plan_id' => 'b',
                'payment_method' => 'spec',
                'entity_guid' => 0,
                'user_guid' => 223,
                'subscription_id' => 'abc1',
                'amount' => new \Cassandra\Decimal(12),
                'interval' => 'monthly',
                'last_billing' => new \Cassandra\Timestamp(time()),
                'next_billing' => new \Cassandra\Timestamp(time()),
                'status' => 'active'
            ]
         ];

        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'select * from subscriptions') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);


        $results = $this->getList();
        $results->shouldHaveCount(2);
        $results[0]->shouldHaveType('Minds\\Core\\Payments\\Subscriptions\\Subscription');
        $results[0]->getPlanId()->shouldBe('a');
        $results[1]->shouldHaveType('Minds\\Core\\Payments\\Subscriptions\\Subscription');
        $results[1]->getPlanId()->shouldBe('b');
    }

    function it_should_select_filtering_by_status()
    {
        $rows = [ 
            [
                'plan_id' => 'a',
                'payment_method' => 'spec',
                'entity_guid' => 0,
                'user_guid' => 123,
                'subscription_id' => 'abc1',
                'amount' => new \Cassandra\Decimal(12),
                'interval' => 'monthly',
                'last_billing' => new \Cassandra\Timestamp(time()),
                'next_billing' => new \Cassandra\Timestamp(time()),
                'status' => 'active'
            ]
         ];

        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'select * from subscriptions') === 0 &&
                $query->build()['values'][0] === 'tested';
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->getList([
                'status' => 'tested'
            ])
            ->shouldHaveCount(1);
    }

    function it_should_select_filtering_by_next_billing()
    {
        $next_billing = strtotime('2000-01-01T12:00:00+00:00');
        $rows = [ 
            [
                'plan_id' => 'a',
                'payment_method' => 'spec',
                'entity_guid' => 0,
                'user_guid' => 123,
                'subscription_id' => 'abc1',
                'amount' => new \Cassandra\Decimal(12),
                'interval' => 'monthly',
                'last_billing' => new \Cassandra\Timestamp(time()),
                'next_billing' => new \Cassandra\Timestamp(time()),
                'status' => 'active'
            ]
         ];

        $this->cql->request(Argument::that(function ($query) use ($next_billing) {
            return stripos($query->build()['string'], 'select * from subscriptions') === 0 &&
                $query->build()['values'][0]->time() == $next_billing;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $results = $this->getList([
            'next_billing' => $next_billing
        ]);
        $results->shouldHaveCount(1);
    }

    function it_should_select_filtering_by_next_billing_using_datetime()
    {
        $next_billing = strtotime('2000-01-01T12:00:00+00:00');
        $rows = [ 
            [
                'plan_id' => 'a',
                'payment_method' => 'spec',
                'entity_guid' => 0,
                'user_guid' => 123,
                'subscription_id' => 'abc1',
                'amount' => new \Cassandra\Decimal(12),
                'interval' => 'monthly',
                'last_billing' => new \Cassandra\Timestamp(time()),
                'next_billing' => new \Cassandra\Timestamp(time()),
                'status' => 'active'
            ]
         ];

        $this->cql->request(Argument::that(function ($query) use ($next_billing) {
            return stripos($query->build()['string'], 'select * from subscriptions') === 0 &&
                $query->build()['values'][0]->time() == $next_billing;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->getList([
                'next_billing' => new \DateTime("@{$next_billing}")
            ])
            ->shouldHaveCount(1);
    }

    function it_should_add(Subscription $subscription, User $user)
    {
        $this->cql->request(Argument::that(function ($query) {
            $values = $query->build()['values'];
            return stripos($query->build()['string'], 'insert into subscriptions') === 0;
            // && $values == [
            //     'sub_abc',
            //     'plan',
            //     'spec',
            //     new \Cassandra\Decimal(20),
            //     new \Cassandra\Varint(0),
            //     new \Cassandra\Varint(1000),
            //     'monthly',
            //     'active',
            //     new \Cassandra\Timestamp(time()),
            //     new \Cassandra\Timestamp(strtotime('+1 month'))
            // ];
            // TODO: Fix comparing instances
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $subscription->getId()
            ->shouldBeCalled()
            ->willReturn('sub_abc');

        $subscription->getPlanId()
            ->shouldBeCalled()
            ->willReturn('plan');

        $subscription->getPaymentMethod()
            ->shouldBeCalled()
            ->willReturn('spec');

        $subscription->getAmount()
            ->shouldBeCalled()
            ->willReturn(20);

        $subscription->getEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $subscription->getUser()
            ->shouldBeCalled()
            ->willReturn($user);

        $subscription->getInterval()
            ->shouldBeCalled()
            ->willReturn('monthly');

        $subscription->getStatus()
            ->shouldBeCalled()
            ->willReturn('active');

        $subscription->getLastBilling()
            ->shouldBeCalled()
            ->willReturn(time());

        $subscription->getNextBilling()
            ->shouldBeCalled()
            ->willReturn(strtotime('+1 month'));

        $this
            ->add($subscription)
            ->shouldReturn(true);
    }

    function it_should_delete(Subscription $subscription, Entity $entity, User $user)
    {
        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'delete from subscriptions') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $entity->get('guid')
            ->shouldBeCalled()
            ->willReturn(5000);

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $subscription->getId()
            ->shouldBeCalled()
            ->willReturn('sub_abc');

        $subscription->getPlanId()
            ->shouldBeCalled()
            ->willReturn('plan');

        $subscription->getPaymentMethod()
            ->shouldBeCalled()
            ->willReturn('spec');

        $subscription->getEntity()
            ->shouldBeCalled()
            ->willReturn($entity);

        $subscription->getUser()
            ->shouldBeCalled()
            ->willReturn($user);

        $this
            ->delete($subscription)
            ->shouldReturn(true);
    }

}
