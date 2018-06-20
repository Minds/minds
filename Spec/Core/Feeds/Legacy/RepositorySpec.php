<?php

namespace Spec\Minds\Core\Feeds\Legacy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Feeds\Legacy\Repository');
    }
}
