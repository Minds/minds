<?php

namespace Spec\Minds\Core\Feeds\Top;

use Minds\Core\Blogs\Blog;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Feeds\Top\Entities;
use Minds\Entities\Activity;
use Minds\Entities\Image;
use Minds\Entities\Video;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntitiesSpec extends ObjectBehavior
{
    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    function let(EntitiesBuilder $entitiesBuilder)
    {
        $this->entitiesBuilder = $entitiesBuilder;
        $this->beConstructedWith($entitiesBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Entities::class);
    }

    function it_should_not_cast_an_activity(Activity $activity)
    {
        $this
            ->cast($activity)
            ->shouldReturn($activity);
    }

    function it_should_cast_an_image(Image $image)
    {
        $image->get('guid')
            ->shouldBeCalled()
            ->willReturn('guid');

        $image->get('time_created')
            ->shouldBeCalled()
            ->willReturn('time_created');

        $image->get('owner_guid')
            ->shouldBeCalled()
            ->willReturn('owner_guid');

        $image->get('container_guid')
            ->shouldBeCalled()
            ->willReturn('container_guid');

        $image->get('access_id')
            ->shouldBeCalled()
            ->willReturn('access_id');

        $image->get('time_updated')
            ->shouldBeCalled()
            ->willReturn('time_updated');

        $image->get('mature')
            ->shouldBeCalled()
            ->willReturn('mature');

        $image->get('spam')
            ->shouldBeCalled()
            ->willReturn('spam');

        $image->get('deleted')
            ->shouldBeCalled()
            ->willReturn('deleted');

        $image->get('paywall')
            ->shouldBeCalled()
            ->willReturn('paywall');

        $image->get('edited')
            ->shouldBeCalled()
            ->willReturn('edited');

        $image->get('comments_enabled')
            ->shouldBeCalled()
            ->willReturn('comments_enabled');

        $image->get('wire_threshold')
            ->shouldBeCalled()
            ->willReturn('wire_threshold');

        $image->get('rating')
            ->shouldBeCalled()
            ->willReturn('rating');

        $image->get('impressions')
            ->shouldBeCalled()
            ->willReturn('impressions');

        $image->get('thumbs:up:user_guids')
            ->shouldBeCalled()
            ->willReturn('thumbs:up:user_guids');

        $image->get('thumbs:up:count')
            ->shouldBeCalled()
            ->willReturn('thumbs:up:count');

        $image->get('thumbs:down:user_guids')
            ->shouldBeCalled()
            ->willReturn('thumbs:down:user_guids');

        $image->get('thumbs:down:count')
            ->shouldBeCalled()
            ->willReturn('thumbs:down:count');

        $image->get('title')
            ->shouldBeCalled()
            ->willReturn('title');

        $image->get('description')
            ->shouldBeCalled()
            ->willReturn('description');

        $image->getFlag('mature')
            ->shouldBeCalled()
            ->willReturn(false);

        $image->get('ownerObj')
            ->shouldBeCalled()
            ->willReturn([]);

        $image->getActivityParameters()
            ->shouldBeCalled()
            ->willReturn(['test', []]);

        $this
            ->cast($image)
            ->shouldReturnAnInstanceOf(Activity::class);
    }

    function it_should_cast_a_video(Video $video)
    {
        $video->get('guid')
            ->shouldBeCalled()
            ->willReturn('guid');

        $video->get('time_created')
            ->shouldBeCalled()
            ->willReturn('time_created');

        $video->get('owner_guid')
            ->shouldBeCalled()
            ->willReturn('owner_guid');

        $video->get('container_guid')
            ->shouldBeCalled()
            ->willReturn('container_guid');

        $video->get('access_id')
            ->shouldBeCalled()
            ->willReturn('access_id');

        $video->get('time_updated')
            ->shouldBeCalled()
            ->willReturn('time_updated');

        $video->get('mature')
            ->shouldBeCalled()
            ->willReturn('mature');

        $video->get('spam')
            ->shouldBeCalled()
            ->willReturn('spam');

        $video->get('deleted')
            ->shouldBeCalled()
            ->willReturn('deleted');

        $video->get('paywall')
            ->shouldBeCalled()
            ->willReturn('paywall');

        $video->get('edited')
            ->shouldBeCalled()
            ->willReturn('edited');

        $video->get('comments_enabled')
            ->shouldBeCalled()
            ->willReturn('comments_enabled');

        $video->get('wire_threshold')
            ->shouldBeCalled()
            ->willReturn('wire_threshold');

        $video->get('rating')
            ->shouldBeCalled()
            ->willReturn('rating');

        $video->get('impressions')
            ->shouldBeCalled()
            ->willReturn('impressions');

        $video->get('thumbs:up:user_guids')
            ->shouldBeCalled()
            ->willReturn('thumbs:up:user_guids');

        $video->get('thumbs:up:count')
            ->shouldBeCalled()
            ->willReturn('thumbs:up:count');

        $video->get('thumbs:down:user_guids')
            ->shouldBeCalled()
            ->willReturn('thumbs:down:user_guids');

        $video->get('thumbs:down:count')
            ->shouldBeCalled()
            ->willReturn('thumbs:down:count');

        $video->get('title')
            ->shouldBeCalled()
            ->willReturn('title');

        $video->get('description')
            ->shouldBeCalled()
            ->willReturn('description');

        $video->getFlag('mature')
            ->shouldBeCalled()
            ->willReturn(false);

        $video->get('ownerObj')
            ->shouldBeCalled()
            ->willReturn([]);

        $video->getActivityParameters()
            ->shouldBeCalled()
            ->willReturn(['test', []]);

        $this
            ->cast($video)
            ->shouldReturnAnInstanceOf(Activity::class);
    }

    function it_should_cast_a_blog(Blog $blog)
    {
        $blog->export()
            ->shouldBeCalled()
            ->willReturn([]);

        $blog->getTitle()
            ->shouldBeCalled()
            ->willReturn('title');

        $blog->getTags()
            ->shouldBeCalled()
            ->willReturn(['test']);

        $blog->getBody()
            ->shouldBeCalled()
            ->willReturn('description');

        $blog->getUrl()
            ->shouldBeCalled()
            ->willReturn('url');

        $blog->getIconUrl()
            ->shouldBeCalled()
            ->willReturn('icon_url');

        $blog->isMature()
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->cast($blog)
            ->shouldReturnAnInstanceOf(Activity::class);
    }
}
