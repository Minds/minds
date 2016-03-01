<?php

namespace Spec\Minds\Plugin\Groups\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Thrift\Indexes;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class FeaturedSpec extends ObjectBehavior
{
    function it_is_initializable(Indexes $db)
    {
        $this->beConstructedWith($db);
        $db->setNamespace('group')->shouldBeCalled();
        $this->shouldHaveType('Minds\Plugin\Groups\Core\Featured');
    }

    function it_should_get_featured(Indexes $db)
    {
        $this->beConstructedWith($db);

        $db->setNamespace('group')->shouldBeCalled();
        $db->get('featured', Argument::any())->shouldBeCalled()->willReturn([98, 99]);
        $this->getFeatured([ 'hydrate' => false ])->shouldReturn([98, 99]);
    }

    function it_should_feature(Indexes $db, GroupEntity $group)
    {
        $this->beConstructedWith($db);

        $group->getGuid()->willReturn(50);
        $group->getFeaturedId()->willReturn(1050);
        $db->setNamespace('group')->shouldBeCalled();
        $db->set('featured', [ 1050 => 50 ])->shouldBeCalled()->willReturn(true);

        $this->feature($group)->shouldReturn(true);
    }

    function it_should_unfeature(Indexes $db, GroupEntity $group)
    {
        $this->beConstructedWith($db);

        $group->getGuid()->willReturn(50);
        $group->getFeaturedId()->willReturn(1050);
        $db->setNamespace('group')->shouldBeCalled();
        $db->remove('featured', [ 1050 ])->shouldBeCalled()->willReturn(true);

        $this->unfeature($group)->shouldReturn(true);
    }
}
