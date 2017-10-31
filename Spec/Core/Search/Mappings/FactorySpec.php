<?php

namespace Spec\Minds\Core\Search\Mappings;

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
        $activity->get('type')
            ->shouldBeCalled()
            ->willReturn('activity');

        $activity->get('subtype')
            ->shouldBeCalled()
            ->willReturn('');

        $activity->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $this->build($activity)
            ->shouldBeAnInstanceOf(Mappings\ActivityMapping::class);
    }

    function it_should_build_a_generic_entity_mapping(Entities\Entity $entity)
    {
        $entity->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $entity->get('type')
            ->shouldBeCalled()
            ->willReturn('something');

        $entity->get('subtype')
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

    function it_should_build_an_object_blog_mapping(Plugin\blog\entities\Blog $blog)
    {
        $blog->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $blog->get('type')
            ->shouldBeCalled()
            ->willReturn('object');

        $blog->get('subtype')
            ->shouldBeCalled()
            ->willReturn('blog');

        $this->build($blog)
            ->shouldBeAnInstanceOf(Mappings\ObjectBlogMapping::class);
    }

    function it_should_build_an_object_image_mapping(Entities\Image $image)
    {
        $image->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $image->get('type')
            ->shouldBeCalled()
            ->willReturn('object');

        $image->get('subtype')
            ->shouldBeCalled()
            ->willReturn('image');

        $this->build($image)
            ->shouldBeAnInstanceOf(Mappings\ObjectImageMapping::class);
    }

    function it_should_build_an_object_video_mapping(Entities\Video $video)
    {
        $video->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $video->get('type')
            ->shouldBeCalled()
            ->willReturn('object');

        $video->get('subtype')
            ->shouldBeCalled()
            ->willReturn('video');

        $this->build($video)
            ->shouldBeAnInstanceOf(Mappings\ObjectVideoMapping::class);
    }

    function it_should_build_an_user_mapping(Entities\User $user)
    {
        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $user->get('type')
            ->shouldBeCalled()
            ->willReturn('user');

        $user->get('subtype')
            ->shouldBeCalled()
            ->willReturn('');

        $this->build($user)
            ->shouldBeAnInstanceOf(Mappings\UserMapping::class);
    }
}
