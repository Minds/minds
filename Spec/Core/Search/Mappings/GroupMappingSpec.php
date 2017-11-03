<?php

namespace Spec\Minds\Core\Search\Mappings;

use Minds\Entities\Group;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GroupMappingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Search\Mappings\GroupMapping');
    }

    //function it_should_map_a_group(
    //    Group $group
    //)
    //{
        // TODO: Find the way to mock __call('getOwnerObj')
    //}
}
