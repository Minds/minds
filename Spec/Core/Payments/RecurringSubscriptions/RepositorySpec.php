<?php

namespace Spec\Minds\Core\Payments\RecurringSubscriptions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\RecurringSubscriptions\Repository');
    }

    function it_should_select()
    {

    }

    function it_should_select_filtering_by_status()
    {

    }

    function it_should_select_filtering_by_next_billing()
    {

    }

    function it_should_upsert()
    {

    }

    function it_should_throw_if_no_type_during_upsert()
    {

    }

    function it_should_throw_if_no_payment_method_during_upsert()
    {

    }

    function it_should_throw_if_no_entity_guid_during_upsert()
    {

    }

    function it_should_throw_if_no_user_guid_during_upsert()
    {

    }

    function it_should_throw_if_no_data_during_upsert()
    {

    }

    function it_should_delete()
    {

    }

    public function it_should_throw_if_no_type_during_delete()
    {

    }

    public function it_should_throw_if_no_payment_method_during_delete()
    {

    }

    public function it_should_throw_if_no_entity_guid_during_delete()
    {

    }

    public function it_should_throw_if_no_user_guid_during_delete()
    {

    }
}
