<?php

namespace Spec\Minds\Core\Payments\RecurringSubscriptions;

use Minds\Core\Di\Di;
use Minds\Core\Payments\Manager;
use Minds\Core\Payments\RecurringSubscriptions\Repository;
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
        $this->shouldHaveType('Minds\Core\Payments\RecurringSubscriptions\Manager');
    }

    function it_should_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(
            $type,
            $payment_method,
            $entity_guid,
            $user_guid,
            $data + [ 'status' => 'active' ]
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->create($data)
            ->shouldReturn($data['subscription_id']);
    }

    function it_should_generate_a_subscription_id_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'amount' => 1,
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(
            $type,
            $payment_method,
            $entity_guid,
            $user_guid,
            Argument::type('array')
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->create($data)
            ->shouldContain('guid:');
    }

    function it_should_inject_the_current_time_into_last_billing_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'next_billing' => 20000000
        ];

        $this->repository->upsert(
            $type,
            $payment_method,
            $entity_guid,
            $user_guid,
            Argument::that(function ($data) {
                return isset($data['last_billing']) && is_numeric($data['last_billing']);
            })
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->create($data)
            ->shouldReturn($data['subscription_id']);
    }

    function it_should_calculate_the_next_billing_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'monthly',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000
        ];

        $this->repository->upsert(
            $type,
            $payment_method,
            $entity_guid,
            $user_guid,
            Argument::that(function ($data) {
                return isset($data['next_billing']) && is_numeric($data['next_billing']);
            })
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->create($data)
            ->shouldReturn($data['subscription_id']);
    }

    function it_should_throw_if_no_type_during_create()
    {
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Type is required'))
            ->duringCreate($data);
    }

    function it_should_throw_if_no_payment_method_during_create()
    {
        $type = 'test';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Payment Method is required'))
            ->duringCreate($data);
    }

    function it_should_throw_if_no_entity_guid_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Entity GUID is required'))
            ->duringCreate($data);
    }

    function it_should_throw_if_no_user_guid_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $data = [
            'recurring' => 'custom',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->shouldThrow(new \Exception('User GUID is required'))
            ->duringCreate($data);
    }

    function it_should_throw_if_no_recurring_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Recurring is invalid'))
            ->duringCreate($data);
    }

    function it_should_throw_if_invalid_recurring_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => '}^invalid-recurring-phpspec',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Recurring is invalid'))
            ->duringCreate($data);
    }

    function it_should_throw_if_no_amount_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Amount is invalid'))
            ->duringCreate($data);
    }

    function it_should_throw_if_invalid_amount_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'amount' => -10,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Amount is invalid'))
            ->duringCreate($data);
    }

    function it_should_throw_if_upsert_fails_during_create()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $data = [
            'recurring' => 'custom',
            'amount' => 1,
            'subscription_id' => 'phpspec:test:5000',
            'last_billing' => 10000000,
            'next_billing' => 20000000
        ];

        $this->repository->upsert(
            $type,
            $payment_method,
            $entity_guid,
            $user_guid,
            $data + [ 'status' => 'active' ]
        )
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Cannot save recurring subscription'))
            ->duringCreate($data);
    }

    function it_should_update_billing()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $last_billing = 10000000;
        $recurring = 'daily';
        $next_billing = $last_billing + (24 * 60 * 60);

        $this->repository->upsert(
            $type,
            $payment_method,
            $entity_guid,
            $user_guid,
            [
                'status' => 'active',
                'last_billing' => $last_billing,
                'next_billing' => $next_billing
            ]
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->updateBilling($last_billing, $recurring)
            ->shouldReturn(true);
    }

    function it_should_throw_if_no_type_during_update_billing()
    {
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $last_billing = 10000000;
        $recurring = 'daily';

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Type is required'))
            ->duringUpdateBilling($last_billing, $recurring);
    }

    function it_should_throw_if_no_payment_method_during_update_billing()
    {
        $type = 'test';
        $entity_guid = 4000;
        $user_guid = 1000;
        $last_billing = 10000000;
        $recurring = 'daily';

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setType($type)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Payment Method is required'))
            ->duringUpdateBilling($last_billing, $recurring);
    }

    function it_should_throw_if_no_entity_guid_during_update_billing()
    {
        $type = 'test';
        $payment_method = 'specs';
        $user_guid = 1000;
        $last_billing = 10000000;
        $recurring = 'daily';

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Entity GUID is required'))
            ->duringUpdateBilling($last_billing, $recurring);
    }

    function it_should_throw_if_no_user_guid_during_update_billing()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $last_billing = 10000000;
        $recurring = 'daily';

        $this->repository->upsert(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->shouldThrow(new \Exception('User GUID is required'))
            ->duringUpdateBilling($last_billing, $recurring);
    }

    function it_should_throw_if_upsert_is_false_during_update_billing()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;
        $last_billing = 10000000;
        $recurring = 'daily';
        $next_billing = $last_billing + (24 * 60 * 60);

        $this->repository->upsert(
            $type,
            $payment_method,
            $entity_guid,
            $user_guid,
            [
                'status' => 'active',
                'last_billing' => $last_billing,
                'next_billing' => $next_billing
            ]
        )
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Cannot update recurring subscription'))
            ->duringUpdateBilling($last_billing, $recurring);

    }

    function it_should_cancel()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;

        $this->repository->delete(
            $type,
            $payment_method,
            $entity_guid,
            $user_guid
        )
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->cancel()
            ->shouldReturn(true);
    }

    function it_should_throw_if_no_type_during_cancel()
    {
        $payment_method = 'specs';
        $entity_guid = 4000;
        $user_guid = 1000;

        $this->repository->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Type is required'))
            ->duringCancel();
    }

    function it_should_throw_if_no_payment_method_during_cancel()
    {
        $type = 'test';
        $entity_guid = 4000;
        $user_guid = 1000;

        $this->repository->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setType($type)
            ->setEntityGuid($entity_guid)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Payment Method is required'))
            ->duringCancel();
    }

    function it_should_throw_if_no_entity_guid_during_cancel()
    {
        $type = 'test';
        $payment_method = 'specs';
        $user_guid = 1000;

        $this->repository->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setUserGuid($user_guid)
            ->shouldThrow(new \Exception('Entity GUID is required'))
            ->duringCancel();
    }

    function it_should_throw_if_no_user_guid_during_cancel()
    {
        $type = 'test';
        $payment_method = 'specs';
        $entity_guid = 4000;

        $this->repository->delete(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->setType($type)
            ->setPaymentMethod($payment_method)
            ->setEntityGuid($entity_guid)
            ->shouldThrow(new \Exception('User GUID is required'))
            ->duringCancel();
    }

    function it_should_create_payment(
        Manager $manager
    )
    {
        $type = 'test';
        $user_guid = 1000;
        $time_created = 10000000;
        $payment_id = 'test:5000';
        $data = [
            'time_created' => $time_created,
            'payment_method' => 'specs',
            'payment_id' => $payment_id,
            'subscription_id' => 'test:phpspec:5000'
        ];

        Di::_()->bind('Payments\Manager', function () use ($manager) {
            return $manager->getWrappedObject();
        });

        $manager->setType($type)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setUserGuid($user_guid)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setTimeCreated($time_created)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setPaymentId($payment_id)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->create($data)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setUserGuid($user_guid)
            ->createPayment($data)
            ->shouldReturn(true);
    }

    function it_should_inject_the_current_time_during_create_payment(
        Manager $manager
    )
    {
        $type = 'test';
        $user_guid = 1000;
        $payment_id = 'test:5000';
        $data = [
            'payment_method' => 'specs',
            'payment_id' => $payment_id,
            'subscription_id' => 'test:phpspec:5000'
        ];

        Di::_()->bind('Payments\Manager', function () use ($manager) {
            return $manager->getWrappedObject();
        });

        $manager->setType($type)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setUserGuid($user_guid)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setTimeCreated(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setPaymentId($payment_id)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->create(Argument::that(function ($data) {
            return isset($data['time_created']) && is_numeric($data['time_created']);
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setUserGuid($user_guid)
            ->createPayment($data)
            ->shouldReturn(true);
    }

    function it_should_inject_the_current_payment_method_during_create_payment(
        Manager $manager
    )
    {
        $type = 'test';
        $user_guid = 1000;
        $time_created = 10000000;
        $payment_id = 'test:5000';
        $data = [
            'time_created' => $time_created,
            'payment_id' => $payment_id,
            'subscription_id' => 'test:phpspec:5000'
        ];
        $payment_method = 'specs';

        Di::_()->bind('Payments\Manager', function () use ($manager) {
            return $manager->getWrappedObject();
        });

        $manager->setType($type)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setUserGuid($user_guid)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setTimeCreated($time_created)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setPaymentId($payment_id)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->create($data + [ 'payment_method' => $payment_method ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setUserGuid($user_guid)
            ->setPaymentMethod($payment_method)
            ->createPayment($data)
            ->shouldReturn(true);
    }

    function it_should_inject_the_current_subscription_id_during_create_payment(
        Manager $manager
    )
    {
        $type = 'test';
        $user_guid = 1000;
        $time_created = 10000000;
        $payment_id = 'test:5000';
        $data = [
            'payment_method' => 'specs',
            'time_created' => $time_created,
            'payment_id' => $payment_id
        ];
        $subscription_id = 'test:phpspec:5000';

        Di::_()->bind('Payments\Manager', function () use ($manager) {
            return $manager->getWrappedObject();
        });

        $manager->setType($type)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setUserGuid($user_guid)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setTimeCreated($time_created)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setPaymentId($payment_id)
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->create($data + [ 'subscription_id' => $subscription_id ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setType($type)
            ->setUserGuid($user_guid)
            ->setSubscriptionId($subscription_id)
            ->createPayment($data)
            ->shouldReturn(true);
    }

    function it_should_get_next_billing_for_daily_recurring()
    {
        $last_billing = strtotime('2000-01-01T12:00:00+00:00');
        $next_billing = strtotime('+1 day', $last_billing);

        $this
            ->getNextBilling($last_billing, 'daily')
            ->shouldReturn($next_billing);
    }

    function it_should_get_next_billing_for_monthly_recurring()
    {
        $last_billing = strtotime('2000-01-01T12:00:00+00:00');
        $next_billing = strtotime('+1 month', $last_billing);

        $this
            ->getNextBilling($last_billing, 'monthly')
            ->shouldReturn($next_billing);
    }

    function it_should_get_next_billing_for_yearly_recurring()
    {
        $last_billing = strtotime('2000-01-01T12:00:00+00:00');
        $next_billing = strtotime('+1 year', $last_billing);

        $this
            ->getNextBilling($last_billing, 'yearly')
            ->shouldReturn($next_billing);
    }

    function it_should_get_next_billing_as_null_for_custom_recurring()
    {
        $last_billing = 10000000;

        $this
            ->getNextBilling($last_billing, 'custom')
            ->shouldReturn(null);
    }

    function it_should_get_next_billing_as_null_for_empty_last_billing()
    {
        $this
            ->getNextBilling(null, 'custom')
            ->shouldReturn(null);
    }

    function it_should_get_next_billing_converting_date_time_to_timestamp()
    {
        $last_billing = strtotime('2000-01-01T12:00:00+00:00');
        $next_billing = strtotime('+1 day', $last_billing);

        $this
            ->getNextBilling(new \DateTime("@{$last_billing}"), 'daily')
            ->shouldReturn($next_billing);
    }

    function it_should_throw_if_invalid_recurring_during_get_next_billing()
    {
        $last_billing = 10000000;

        $this
            ->shouldThrow(new \Exception('Invalid recurring value'))
            ->duringGetNextBilling($last_billing, '^}invalid-recurring-value');
    }
}
