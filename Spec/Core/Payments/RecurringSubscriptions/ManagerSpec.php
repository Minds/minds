<?php

namespace Spec\Minds\Core\Payments\RecurringSubscriptions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\RecurringSubscriptions\Manager');
    }

    function it_should_create()
    {

    }

    function it_should_create_generating_a_subscription_id()
    {

    }

    function it_should_create_injecting_the_current_time()
    {

    }

    function it_should_throw_if_no_type_during_create()
    {

    }

    function it_should_throw_if_no_payment_method_during_create()
    {

    }

    function it_should_throw_if_no_entity_guid_during_create()
    {

    }

    function it_should_throw_if_no_user_guid_during_create()
    {

    }

    function it_should_throw_if_no_recurring_during_create()
    {

    }

    function it_should_throw_if_no_amount_during_create()
    {

    }

    function it_should_throw_if_invalid_amount_during_create()
    {

    }

    function it_should_throw_if_upsert_fails_during_create()
    {

    }

    function it_should_cancel()
    {

    }

    function it_should_throw_if_no_type_during_cancel()
    {

    }

    function it_should_throw_if_no_payment_method_during_cancel()
    {

    }

    function it_should_throw_if_no_entity_guid_during_cancel()
    {

    }

    function it_should_throw_if_no_user_guid_during_cancel()
    {

    }

    function it_should_create_payment()
    {

    }

    function it_should_get_next_billing_for_daily_recurring()
    {

    }

    function it_should_get_next_billing_for_monthly_recurring()
    {

    }

    function it_should_get_next_billing_for_yearly_recurring()
    {

    }

    function it_should_get_next_billing_as_null_for_custom_recurring()
    {

    }

    function it_should_get_next_billing_as_null_for_empty_last_billing()
    {

    }

    function it_should_get_next_billing_converting_date_time_to_timestamp()
    {

    }

    function it_should_throw_if_invalid_recurring_during_get_next_billing()
    {

    }
}
