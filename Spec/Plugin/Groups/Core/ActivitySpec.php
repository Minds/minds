<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Core\Data\Cassandra\Thrift\Indexes;

class ActivitySpec extends ObjectBehavior
{
    function it_is_initializable(GroupEntity $entity, Indexes $db)
    {
        $this->beConstructedWith($entity, $db);
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Activity');
    }

    function it_should_get_activity_count(GroupEntity $group, Indexes $db)
    {
        $this->beConstructedWith($group, $db);

        $group->getGuid()->willReturn(50);
        $db->count('activity:container:50')->shouldBeCalled()->willReturn(5);

        $this->count()->shouldReturn(5);
    }
}
