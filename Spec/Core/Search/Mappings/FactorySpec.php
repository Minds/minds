<?php

namespace Spec\Minds\Core\Search\Mappings;

use Minds\Core\Blogs\Blog;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Search\Mappings;
use Minds\Entities;
use Minds\Plugin;

class FactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Search\Mappings\Factory');
    }

    function it_should_build_an_activity_mapping(Entities\Activity $activity)
    {
        $activity->getType()
            ->shouldBeCalled()
            ->willReturn('activity');

        $activity->getSubtype()
            ->shouldBeCalled()
            ->willReturn('');

        $activity->getGUID()
            ->shouldBeCalled()
            ->willReturn(1000);

        $this->build($activity)
            ->shouldBeAnInstanceOf(Mappings\ActivityMapping::class);
    }

    function it_should_build_a_generic_entity_mapping(Entities\Entity $entity)
    {
        $entity->getGUID()
            ->shouldBeCalled()
            ->willReturn(1000);

        $entity->getType()
            ->shouldBeCalled()
            ->willReturn('something');

        $entity->getSubtype()
            ->shouldBeCalled()
            ->willReturn('');

        $this->build($entity)
            ->shouldBeAnInstanceOf(Mappings\EntityMapping::class);
    }

    function it_should_build_a_group_mapping(Entities\Group $group)
    {
        $group->getGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $group->getType()
            ->shouldBeCalled()
            ->willReturn('group');

        $this->build($group)
            ->shouldBeAnInstanceOf(Mappings\GroupMapping::class);
    }

    function it_should_build_an_object_blog_mapping(Blog $blog)
    {
        $blog->getGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $blog->getType()
            ->shouldBeCalled()
            ->willReturn('object');

        $blog->getSubtype()
            ->shouldBeCalled()
            ->willReturn('blog');

        $this->build($blog)
            ->shouldBeAnInstanceOf(Mappings\ObjectBlogMapping::class);
    }

    function it_should_build_an_object_image_mapping(Entities\Image $image)
    {
        $image->getGUID()
            ->shouldBeCalled()
            ->willReturn(1000);

        $image->getType()
            ->shouldBeCalled()
            ->willReturn('object');

        $image->getSubtype()
            ->shouldBeCalled()
            ->willReturn('image');

        $this->build($image)
            ->shouldBeAnInstanceOf(Mappings\ObjectImageMapping::class);
    }

    function it_should_build_an_object_video_mapping(Entities\Video $video)
    {
        $video->getGUID()
            ->shouldBeCalled()
            ->willReturn(1000);

        $video->getType()
            ->shouldBeCalled()
            ->willReturn('object');

        $video->getSubtype()
            ->shouldBeCalled()
            ->willReturn('video');

        $this->build($video)
            ->shouldBeAnInstanceOf(Mappings\ObjectVideoMapping::class);
    }

    function it_should_build_an_user_mapping(Entities\User $user)
    {
        $user->getGUID()
            ->shouldBeCalled()
            ->willReturn(1000);

        $user->getType()
            ->shouldBeCalled()
            ->willReturn('user');

        $user->getSubtype()
            ->shouldBeCalled()
            ->willReturn('');

        $this->build($user)
            ->shouldBeAnInstanceOf(Mappings\UserMapping::class);
    }
}
