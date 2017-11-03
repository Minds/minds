<?php

namespace Spec\Minds\Core\Search\Mappings;

use Minds\Entities\Blog;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectBlogMappingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Search\Mappings\ObjectBlogMapping');
    }

    function it_should_map_a_blog(
        Blog $blog
    )
    {
        $now = time();

        $blog->get('interactions')->willReturn(42);
        $blog->get('guid')->willReturn(5000);
        $blog->get('type')->willReturn('object');
        $blog->get('subtype')->willReturn('blog');
        $blog->get('time_created')->willReturn($now);
        $blog->get('access_id')->willReturn(2);
        $blog->get('owner_guid')->willReturn(1000);
        $blog->get('container_guid')->willReturn(1000);
        $blog->get('mature')->willReturn(false);
        $blog->get('message')->willReturn('PHPSpec Message #test #hashtag');
        $blog->get('name')->willReturn('PHPSpec Name');
        $blog->get('title')->willReturn('PHPSpec Title');
        $blog->get('blurb')->willReturn('PHPSpec Blurb');
        $blog->get('description')->willReturn('PHPSpec Description');
        $blog->get('paywall')->willReturn(false);
        $blog->get('license')->willReturn('cc-test-lic');

        $blog->getMature()->willReturn(false);

        $this
            ->setEntity($blog)
            ->map([
                'passedValue' => 'PHPSpec',
                'guid' => '4999-will-disappear'
            ])
            ->shouldReturn([
                'passedValue' => 'PHPSpec',
                'guid' => '5000',
                'interactions' => 42,
                'type' => 'object',
                'subtype' => 'blog',
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
                '@timestamp' => $now * 1000,
                'taxonomy' => 'object:blog',
                'public' => true,
                'tags' => [ 'test', 'hashtag' ]
            ]);
    }
}
