<?php

namespace Spec\Minds\Core\Payments;

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
        $this->shouldHaveType('Minds\Core\Payments\Repository');
    }

    function it_should_get_by_payment_id()
    {
        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'select * from payments_by_payment_id') === 0 &&
                $query->build()['values'][0] == 'test:5000';
        }))
            ->shouldBeCalled()
            ->willReturn([ 1337 ]);

        $this
            ->getByPaymentId('test:5000')
            ->shouldReturn(1337);
    }

    function it_should_return_false_if_request_is_falsy_during_get_payment_by_id()
    {
        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'select * from payments_by_payment_id') === 0 &&
                $query->build()['values'][0] == 'test:5000';
        }))
            ->shouldBeCalled()
            ->willReturn([]);

        $this
            ->getByPaymentId('test:5000')
            ->shouldReturn(false);
    }

    function it_should_throw_if_no_payment_id_during_get_payment_by_id()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Payment ID is required'))
            ->duringGetByPaymentId(null);
    }

    function it_should_upsert()
    {
        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'insert into payments') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->upsert('test', 1000, 10000000, 'test:5000', [ 'amount' => 1 ])
            ->shouldReturn(true);
    }

    function it_should_return_false_if_request_is_falsy_during_upsert()
    {
        $this->cql->request(Argument::that(function ($query) {
            return stripos($query->build()['string'], 'insert into payments') === 0;
        }))
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->upsert('test', 1000, 10000000, 'test:5000', [ 'amount' => 1 ])
            ->shouldReturn(false);
    }

    function it_should_throw_if_no_type_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Type is required'))
            ->duringUpsert(null, 1000, 10000000, 'test:5000', [ 'amount' => 1 ]);
    }

    function it_should_throw_if_no_user_guid_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('User GUID is required'))
            ->duringUpsert('test', null, 10000000, 'test:5000', [ 'amount' => 1 ]);
    }

    function it_should_throw_if_no_time_created_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Time Created is required'))
            ->duringUpsert('test', 1000, null, 'test:5000', [ 'amount' => 1 ]);
    }

    function it_should_throw_if_no_payment_id_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Payment ID is required'))
            ->duringUpsert('test', 1000, 10000000, null, [ 'amount' => 1 ]);
    }

    function it_should_throw_if_no_data_during_upsert()
    {
        $this->cql->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new \Exception('Data set is required'))
            ->duringUpsert('test', 1000, 10000000, 'test:5000', [ ]);
    }
}
