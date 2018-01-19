<?php

namespace Spec\Minds\Core\Search\Mappings;

use Minds\Entities\Image;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectImageMappingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Search\Mappings\ObjectImageMapping');
    }

    function it_should_map_an_image(
        Image $image
    )
    {
        $now = time();

        $image->get('rating')->willReturn(1);
        $image->get('interactions')->willReturn(42);
        $image->get('guid')->willReturn(5000);
        $image->get('type')->willReturn('object');
        $image->get('subtype')->willReturn('image');
        $image->get('time_created')->willReturn($now);
        $image->get('access_id')->willReturn(2);
        $image->get('owner_guid')->willReturn(1000);
        $image->get('container_guid')->willReturn(1000);
        $image->get('mature')->willReturn(false);
        $image->get('message')->willReturn('PHPSpec Message #test #hashtag');
        $image->get('name')->willReturn('PHPSpec Name');
        $image->get('title')->willReturn('PHPSpec Title');
        $image->get('blurb')->willReturn('PHPSpec Blurb');
        $image->get('description')->willReturn('PHPSpec Description');
        $image->get('paywall')->willReturn(false);
        $image->get('license')->willReturn('cc-test-lic');

        $image->getFlag('mature')->willReturn(false);
        $image->getFlag('paywall')->willReturn(false);

        $this
            ->setEntity($image)
            ->map([
                'passedValue' => 'PHPSpec',
                'guid' => '4999-will-disappear'
            ])
            ->shouldReturn([
                'passedValue' => 'PHPSpec',
                'guid' => '5000',
                'interactions' => 42,
                'type' => 'object',
                'subtype' => 'image',
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
                'taxonomy' => 'object:image',
                'public' => true,
                'tags' => [ 'test', 'hashtag' ]
            ]);
    }
}
