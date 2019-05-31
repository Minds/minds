<?php

namespace Spec\Minds\Core\Entities\Actions;

use Minds\Core\Blogs\Blog;
use Minds\Core\Entities\Actions\Save;
use Minds\Core\Events\EventsDispatcher;
use Minds\Entities\User;
use Minds\Entities\Activity;
use Minds\Entities\Group;
use PhpSpec\ObjectBehavior;

class SaveSpec extends ObjectBehavior
{
    /** @var EventsDispatcher */
    protected $dispatcher;

    public function let(EventsDispatcher $dispatcher)
    {
        $this->beConstructedWith($dispatcher);
        $this->dispatcher = $dispatcher;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Save::class);
    }

    public function it_should_save_an_entity_using_its_save_method(User $user)
    {
        $user->getOwnerEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $user->getContainerEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $user->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $user->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $user->setNsfw([])->shouldBeCalled();

        $user->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setEntity($user);

        $this->save()->shouldReturn(true);
    }

    public function it_should_saev_an_entity_via_the_entity_save_event(Blog $blog)
    {
        $blog->getOwnerEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $blog->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $blog->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $blog->setNsfw([])->shouldBeCalled();

        $this->dispatcher->trigger('entity:save', 'object:blog', ['entity' => $blog], false)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setEntity($blog);
        $this->save($blog)->shouldReturn(true);
    }
    
    public function it_should_save_an_entity_using_its_save_method_with_NSFW_from_owner(Activity $activity, User $owner)
    {
        $nsfw = [1, 2, 3, 4, 5, 6];
        $owner->getNsfw()
            ->shouldBeCalled()
            ->willReturn($nsfw);

        $owner->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $owner->isMature()
            ->shouldBeCalled()
            ->willReturn(false);

        $activity->getOwnerEntity()
            ->shouldBeCalled()
            ->willReturn($owner);

        $activity->getContainerEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->setNsfw($nsfw)->shouldBeCalled();

        $activity->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setEntity($activity);

        $this->save()->shouldReturn(true);
    }

    public function it_should_save_an_entity_using_its_save_method_with_NsfwLock_from_owner(Activity $activity, User $owner)
    {
        $nsfw = [1, 2, 3, 4, 5, 6];
        $owner->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $owner->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn($nsfw);

        $owner->isMature()
            ->shouldBeCalled()
            ->willReturn(false);

        $activity->getOwnerEntity()
            ->shouldBeCalled()
            ->willReturn($owner);

        $activity->getContainerEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->setNsfw($nsfw)->shouldBeCalled();

        $activity->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setEntity($activity);

        $this->save()->shouldReturn(true);
    }

    public function it_should_save_an_entity_using_its_save_method_with_NSFW_from_container(Activity $activity, Group $container)
    {
        $nsfw = [1, 2, 3, 4, 5, 6];
        $container->getNsfw()
            ->shouldBeCalled()
            ->willReturn($nsfw);

        $container->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->getOwnerEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->getContainerEntity()
            ->shouldBeCalled()
            ->willReturn($container);

        $activity->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->setNsfw($nsfw)->shouldBeCalled();

        $activity->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setEntity($activity);

        $this->save()->shouldReturn(true);
    }

    public function it_should_save_an_entity_using_its_save_method_with_NSFW_from_group(Activity $activity, Group $container)
    {
        $nsfw = [1, 2, 3, 4, 5, 6];
        $container->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $container->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn($nsfw);

        $activity->getOwnerEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->getContainerEntity()
            ->shouldBeCalled()
            ->willReturn($container);

        $activity->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->setNsfw($nsfw)->shouldBeCalled();

        $activity->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setEntity($activity);

        $this->save()->shouldReturn(true);
    }


    public function it_should_save_an_entity_using_its_save_method_with_merged_NSFW_from_container(Activity $activity, Group $container)
    {
        $nsfw = [1, 2, 3];
        $nsfwLock = [4, 5, 6];

        $container->getNsfw()
            ->shouldBeCalled()
            ->willReturn($nsfw);

        $container->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn($nsfwLock);

        $activity->getOwnerEntity()
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->getContainerEntity()
            ->shouldBeCalled()
            ->willReturn($container);

        $activity->getNsfw()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->getNsfwLock()
            ->shouldBeCalled()
            ->willReturn([]);

        $activity->setNsfw(array_merge($nsfw, $nsfwLock))->shouldBeCalled();

        $activity->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setEntity($activity);

        $this->save()->shouldReturn(true);
    }

}
