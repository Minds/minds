<?php

namespace Spec\Minds\Core\Search\Mappings;

use Minds\Entities\Video;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectVideoMappingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Search\Mappings\ObjectVideoMapping');
    }

    function it_should_map_a_video(
        Video $video
    )
    {
        $now = time();

        $video->get('rating')->willReturn(1);
        $video->get('interactions')->willReturn(42);
        $video->get('guid')->willReturn(5000);
        $video->get('type')->willReturn('object');
        $video->get('subtype')->willReturn('video');
        $video->get('time_created')->willReturn($now);
        $video->get('access_id')->willReturn(2);
        $video->get('owner_guid')->willReturn(1000);
        $video->get('container_guid')->willReturn(1000);
        $video->get('mature')->willReturn(false);
        $video->get('message')->willReturn('PHPSpec Message #test #hashtag');
        $video->get('name')->willReturn('PHPSpec Name');
        $video->get('title')->willReturn('PHPSpec Title');
        $video->get('blurb')->willReturn('PHPSpec Blurb');
        $video->get('description')->willReturn('PHPSpec Description');
        $video->get('paywall')->willReturn(false);
        $video->get('license')->willReturn('cc-test-lic');

        $video->getFlag('mature')->willReturn(false);
        $video->getFlag('paywall')->willReturn(false);

        $this
            ->setEntity($video)
            ->map([
                'passedValue' => 'PHPSpec',
                'guid' => '4999-will-disappear'
            ])
            ->shouldReturn([
                'passedValue' => 'PHPSpec',
                'guid' => '5000',
                'interactions' => 42,
                'type' => 'object',
                'subtype' => 'video',
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
                'license' => 'cc-test-lic',
                'rating' => 1,
                '@timestamp' => $now * 1000,
                'taxonomy' => 'object:video',
                'public' => true,
                'tags' => [ 'test', 'hashtag' ]
            ]);
    }
}
