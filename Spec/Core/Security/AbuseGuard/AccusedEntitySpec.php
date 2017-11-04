<?php

namespace Spec\Minds\Core\Security\AbuseGuard;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AccusedEntitySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\AbuseGuard\AccusedEntity');
    }

    function it_should_set_a_user_by_guid()
    {
        $this->setUserGuid('123')->shouldReturn($this);
        $this->getUser()->shouldReturnAnInstanceOf('Minds\Entities\User');
    }

    function it_should_return_a_correct_score()
    {   
        $this->setUserGuid('123')->shouldReturn($this);
        $this->setMetric('boo', 1);
        $this->getScore()->shouldbe(1);
    }
}
