<?php

namespace Spec\Minds\Core\Pages;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data;

class ManagerSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(new Data\Call('entities_by_time'), new Data\Call('user_index_to_guid'), true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Pages\Manager');
    }
}
