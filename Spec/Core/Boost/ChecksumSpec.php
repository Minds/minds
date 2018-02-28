<?php

namespace Spec\Minds\Core\Boost;

use Minds\Entities\Activity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChecksumSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Checksum');
    }

    function it_should_generate(
        Activity $activity
    )
    {
        $activity->get('type')->willReturn('activity');
        $activity->get('guid')->willReturn(5000);
        $activity->get('owner_guid')->willReturn(1000);
        $activity->get('perma_url')->willReturn('http://phpspec');
        $activity->get('message')->willReturn('phpspec');
        $activity->get('title')->willReturn('test');
        $activity->get('time_created')->willReturn(1000000);

        $this
            ->setGuid(6000)
            ->setEntity($activity)
            ->generate()
            ->shouldReturn('96672d8063790730d769b04e5ff331a9');
    }

    function it_should_throw_if_no_guid_during_generate(
        Activity $activity
    )
    {
        $this
            ->setGuid(null)
            ->setEntity($activity)
            ->shouldThrow(new \Exception('GUID is required'))
            ->duringGenerate();
    }

    function it_should_throw_if_no_entity_during_generate()
    {
        $this
            ->setGuid(6000)
            ->setEntity(null)
            ->shouldThrow(new \Exception('Entity is required'))
            ->duringGenerate();
    }
}
