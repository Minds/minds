<?php

namespace Spec\Minds\Core\Payments\RecurringSubscriptions;

use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
        $this->shouldHaveType('Minds\Core\Payments\RecurringSubscriptions\Repository');
    }

    function it_should_select()
    {
        $rows = [ true, true, true ];

        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'select * from recurring_subscriptions') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->select()
            ->shouldReturn($rows);
    }

    function it_should_select_filtering_by_status()
    {
        $rows = [ true, true, true ];

        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'select * from recurring_subscriptions') === 0 &&
                $query->build()['values'][0] === 'tested';
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->select([
            'status' => 'tested'
        ])
            ->shouldReturn($rows);
    }

    function it_should_select_filtering_by_next_billing()
    {
        $next_billing = strtotime('2000-01-01T12:00:00+00:00');
        $rows = [ true, true, true ];

        $this->cql->request(Argument::that(function ($query) use ($next_billing) {
            return stripos($query->build()['string'], 'select * from recurring_subscriptions') === 0 &&
                $query->build()['values'][0]->time() == $next_billing;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->select([
            'next_billing' => $next_billing
        ])
            ->shouldReturn($rows);
    }

    function it_should_select_filtering_by_next_billing_using_datetime()
    {
        $next_billing = strtotime('2000-01-01T12:00:00+00:00');
        $rows = [ true, true, true ];

        $this->cql->request(Argument::that(function ($query) use ($next_billing) {
            return stripos($query->build()['string'], 'select * from recurring_subscriptions') === 0 &&
                $query->build()['values'][0]->time() == $next_billing;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->select([
            'next_billing' => new \DateTime("@{$next_billing}")
        ])
            ->shouldReturn($rows);
    }

    function it_should_upsert()
    {
        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'insert into recurring_subscriptions') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->upsert('test', 'specs', 4000, 1000, [ 'amount' => 1 ])
            ->shouldReturn(true);
    }

    function it_should_throw_if_no_type_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Type is required'))
            ->duringUpsert(null, 'specs', 4000, 1000, [ 'amount' => 1 ]);
    }

    function it_should_throw_if_no_payment_method_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Payment Method is required'))
            ->duringUpsert('test', null, 4000, 1000, [ 'amount' => 1 ]);
    }

    function it_should_throw_if_no_entity_guid_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Entity GUID is required'))
            ->duringUpsert('test', 'specs', null, 1000, [ 'amount' => 1 ]);
    }

    function it_should_throw_if_no_user_guid_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('User GUID is required'))
            ->duringUpsert('test', 'specs', 4000, null, [ 'amount' => 1 ]);
    }

    function it_should_throw_if_no_data_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Data set is required'))
            ->duringUpsert('test', 'specs', 4000, 1000, [ ]);
    }

    function it_should_delete()
    {
        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'delete from recurring_subscriptions') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->delete('test', 'specs', 4000, 1000)
            ->shouldReturn(true);
    }

    public function it_should_throw_if_no_type_during_delete()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Type is required'))
            ->duringDelete(null, 'specs', 4000, 1000);
    }

    public function it_should_throw_if_no_payment_method_during_delete()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Payment Method is required'))
            ->duringDelete('test', null, 4000, 1000);
    }

    public function it_should_throw_if_no_entity_guid_during_delete()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Entity GUID is required'))
            ->duringDelete('test', 'specs', null, 1000);
    }

    public function it_should_throw_if_no_user_guid_during_delete()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('User GUID is required'))
            ->duringDelete('test', 'specs', 4000, null);
    }
}
