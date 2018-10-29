<?php

namespace Spec\Minds\Core\Data;

use Minds\Core\Data\cache\Redis;
use Minds\Core\Data\Call;
use Minds\Core\Data\Sessions;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SessionsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Sessions::class);
    }

}
