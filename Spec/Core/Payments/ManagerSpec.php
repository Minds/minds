<?php

namespace Spec\Minds\Core\Payments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\Manager');
    }

    function it_should_create()
    {

    }

    function it_should_create_generating_a_payment_id()
    {

    }

    function it_should_throw_if_no_type_during_create()
    {

    }

    function it_should_throw_if_no_user_guid_during_create()
    {

    }

    function it_should_throw_if_no_time_created_during_create()
    {

    }

    function it_should_throw_if_upsert_fails_during_create()
    {

    }
}
