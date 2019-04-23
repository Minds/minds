<?php

namespace Spec\Minds\Core\Boost\Network;

use Minds\Core\Boost\Network\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }
}
