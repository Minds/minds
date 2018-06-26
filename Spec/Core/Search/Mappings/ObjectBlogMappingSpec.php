<?php

namespace Spec\Minds\Core\Search\Mappings;

use Minds\Core\Blogs\Blog;
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

        $blog->getInteractions()->willReturn(42);
        $blog->getGuid()->willReturn(5000);
        $blog->getType()->willReturn('object');
        $blog->getSubtype()->willReturn('blog');
        $blog->getTimeCreated()->willReturn($now);
        $blog->getAccessId()->willReturn(2);
        $blog->getOwnerGuid()->willReturn(1000);
        $blog->getContainerGuid()->willReturn(1000);
        $blog->isMature()->willReturn(false);
        $blog->getTitle()->willReturn('PHPSpec Title #test #hashtag');
        $blog->getBody()->willReturn('PHPSpec Description');
        $blog->isPaywall()->willReturn(false);
        $blog->getLicense()->willReturn('cc-test-lic');
        $blog->isMature()->willReturn(false);

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
                'title' => 'PHPSpec Title #test #hashtag',
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
