<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Thrift\Relationships;
use Minds\Plugin\Groups\Core\Featured;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class GroupSpec extends ObjectBehavior
{
    function it_is_initializable(Relationships $db, GroupEntity $group)
    {
        $this->beConstructedWith($group, $db);
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Group');
    }

    function it_should_feature(Relationships $db, GroupEntity $group, Featured $featured)
    {
        $this->beConstructedWith($group, $db, $featured);

        $group->setFeatured(1)->shouldBeCalled();
        $group->getFeaturedId()->shouldBeCalled()->willReturn(1050);
        $group->save()->shouldBeCalled()->willReturn(true);

        $featured->feature($group)->shouldBeCalled();

        $this->feature()->shouldReturn(1050);
    }

    function it_should_unfeature(Relationships $db, GroupEntity $group, Featured $featured)
    {
        $this->beConstructedWith($group, $db, $featured);

        $group->setFeatured(0)->shouldBeCalled();
        $group->setFeaturedId(null)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $featured->unfeature($group)->shouldBeCalled();

        $this->unfeature()->shouldReturn(true);
    }

    function it_should_delete(Relationships $db, GroupEntity $group, Featured $featured)
    {
        $this->beConstructedWith($group, $db, $featured);

        $group->delete()->shouldBeCalled()->willReturn(true);

        $featured->unfeature($group)->shouldBeCalled();

        $this->delete()->shouldReturn(true);
    }
}
