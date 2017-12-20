<?php

namespace Spec\Minds\Core\Payments;

use Minds\Core\Payments\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
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
        $this->shouldHaveType('Minds\Core\Payments\Manager');
    }

    function it_should_create()
    {
        $type = 'test';
        $user_guid = 1000;
        $time_created = 10000000;
        $payment_id = 'test:5000';
        $data = [ 'foo' => 'bar' ];

        $this->repository->upsert(
            $type,
            $user_guid,
            $time_created,
            $payment_id,
            $data
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setUserGuid($user_guid)
            ->setTimeCreated($time_created)
            ->setPaymentId($payment_id)
            ->create($data)
            ->shouldReturn($payment_id);
    }

    function it_should_create_generating_a_payment_id()
    {
        $type = 'test';
        $user_guid = 1000;
        $time_created = 10000000;
        $data = [ 'foo' => 'bar' ];

        $this->repository->upsert(
            $type,
            $user_guid,
            $time_created,
            Argument::containingString('guid:'),
            $data
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setUserGuid($user_guid)
            ->setTimeCreated($time_created)
            ->create($data)
            ->shouldReturn($this->getPaymentId());
    }

    function it_should_throw_if_no_type_during_create()
    {
        $user_guid = 1000;
        $time_created = 10000000;
        $payment_id = 'test:5000';
        $data = [ 'foo' => 'bar' ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setUserGuid($user_guid)
            ->setTimeCreated($time_created)
            ->setPaymentId($payment_id)
            ->shouldThrow(new \Exception('Type is required'))
            ->duringCreate($data);
    }

    function it_should_throw_if_no_user_guid_during_create()
    {
        $type = 'test';
        $time_created = 10000000;
        $payment_id = 'test:5000';
        $data = [ 'foo' => 'bar' ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setType($type)
            ->setTimeCreated($time_created)
            ->setPaymentId($payment_id)
            ->shouldThrow(new \Exception('User GUID is required'))
            ->duringCreate($data);
    }

    function it_should_throw_if_no_time_created_during_create()
    {
        $type = 'test';
        $user_guid = 1000;
        $payment_id = 'test:5000';
        $data = [ 'foo' => 'bar' ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setType($type)
            ->setUserGuid($user_guid)
            ->setPaymentId($payment_id)
            ->shouldThrow(new \Exception('Time created is required'))
            ->duringCreate($data);

    }

    function it_should_throw_if_upsert_fails_during_create()
    {
        $type = 'test';
        $user_guid = 1000;
        $time_created = 10000000;
        $payment_id = 'test:5000';
        $data = [ 'foo' => 'bar' ];

        $this->repository->upsert(
            $type,
            $user_guid,
            $time_created,
            $payment_id,
            $data
        )
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->setType($type)
            ->setUserGuid($user_guid)
            ->setTimeCreated($time_created)
            ->setPaymentId($payment_id)
            ->shouldThrow(new \Exception('Cannot save payment'))
            ->duringCreate($data);
    }

    function it_should_update_payment_by_id()
    {
        $payment_id = 'test:5000';
        $payment_row = [
            'type' => 'test',
            'user_guid' => 1000,
            'time_created' => 10000000
        ];
        $data = [ 'foo' => 'bar' ];

        $this->repository->getByPaymentId($payment_id)
            ->shouldBeCalled()
            ->willReturn($payment_row);

        $this->repository->upsert(
            $payment_row['type'],
            $payment_row['user_guid'],
            $payment_row['time_created'],
            $payment_id,
            $data
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setPaymentId($payment_id)
            ->updatePaymentById($data)
            ->shouldReturn($payment_id);
    }

    function it_should_return_false_if_no_row_during_update_payment_by_id()
    {
        $payment_id = 'test:5000';
        $payment_row = false;
        $data = [ 'foo' => 'bar' ];

        $this->repository->getByPaymentId($payment_id)
            ->shouldBeCalled()
            ->willReturn($payment_row);

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setPaymentId($payment_id)
            ->updatePaymentById($data)
            ->shouldReturn(false);
    }

    function it_should_throw_if_upsert_fails_during_update_payment_by_id()
    {
        $payment_id = 'test:5000';
        $payment_row = [
            'type' => 'test',
            'user_guid' => 1000,
            'time_created' => 10000000
        ];
        $data = [ 'foo' => 'bar' ];

        $this->repository->getByPaymentId($payment_id)
            ->shouldBeCalled()
            ->willReturn($payment_row);

        $this->repository->upsert(
            $payment_row['type'],
            $payment_row['user_guid'],
            $payment_row['time_created'],
            $payment_id,
            $data
        )
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->setPaymentId($payment_id)
            ->shouldThrow(new \Exception('Cannot update payment'))
            ->duringUpdatePaymentById($data);
    }
}
