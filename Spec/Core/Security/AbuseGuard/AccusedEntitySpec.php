<?php

namespace Spec\Minds\Core\Security\AbuseGuard;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Di\Di;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AccusedEntitySpec extends ObjectBehavior
{
    function let(Client $client)
    {
        Di::_()->bind('Database\Cassandra\Entities', function ($di) use ($client) {
            return $client->getWrappedObject();
        });
    }

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
