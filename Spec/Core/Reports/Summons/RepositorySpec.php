<?php

namespace Spec\Minds\Core\Reports\Summons;

use Minds\Core\Reports\Summons\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }
}
