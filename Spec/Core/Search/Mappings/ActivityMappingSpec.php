<?php

namespace Spec\Minds\Core\Search\Mappings;

use Minds\Entities\Activity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActivityMappingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Search\Mappings\ActivityMapping');
    }

    function it_should_map_an_activity(
        Activity $activity
    )
    {
        $now = time();

        $activity->get('rating')->willReturn(1);
        $activity->get('interactions')->willReturn(42);
        $activity->get('guid')->willReturn(5000);
        $activity->get('type')->willReturn('activity');
        $activity->get('subtype')->willReturn('');
        $activity->get('time_created')->willReturn($now);
        $activity->get('access_id')->willReturn(2);
        $activity->get('owner_guid')->willReturn(1000);
        $activity->get('container_guid')->willReturn(1000);
        $activity->get('mature')->willReturn(false);
        $activity->get('message')->willReturn('PHPSpec Message #test #hashtag');
        $activity->get('name')->willReturn('PHPSpec Name');
        $activity->get('title')->willReturn('PHPSpec Title');
        $activity->get('blurb')->willReturn('PHPSpec Blurb');
        $activity->get('description')->willReturn('PHPSpec Description');
        $activity->get('paywall')->willReturn(false);

        $activity->isPayWall()->willReturn(false);
        $activity->getMature()->willReturn(false);

        $this
            ->setEntity($activity)
            ->map([
                'passedValue' => 'PHPSpec',
                'guid' => '4999-will-disappear'
            ])
            ->shouldReturn([
                'passedValue' => 'PHPSpec',
                'guid' => '5000',
                'interactions' => 42,
                'type' => 'activity',
                'subtype' => '',
                'time_created' => $now,
                'access_id' => '2',
                'owner_guid' => '1000',
                'container_guid' => '1000',
                'mature' => false,
                'message' => 'PHPSpec Message #test #hashtag',
                'name' => 'PHPSpec Name',
                'title' => 'PHPSpec Title',
                'blurb' => 'PHPSpec Blurb',
                'description' => 'PHPSpec Description',
                'paywall' => false,
                'rating' => 1,
                '@timestamp' => $now * 1000,
                'taxonomy' => 'activity',
                'public' => true,
                'tags' => [ 'test', 'hashtag' ]
            ]);
    }
}
