<?php

namespace Spec\Minds\Core\Payments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\Repository');
    }

    function it_should_upsert()
    {

    }

    function it_should_throw_if_no_type_during_upsert()
    {

    }

    function it_should_throw_if_no_user_guid_during_upsert()
    {

    }

    function it_should_throw_if_no_time_created_during_upsert()
    {

    }

    function it_should_throw_if_no_payment_id_during_upsert()
    {

    }

    function it_should_throw_if_no_data_during_upsert()
    {

    }
}
