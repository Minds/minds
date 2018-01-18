<?php

namespace Spec\Minds\Core\Blockchain\Transactions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Transactions\Manager');
    }
}
