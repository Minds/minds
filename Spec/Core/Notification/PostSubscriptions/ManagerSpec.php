<?php

namespace Spec\Minds\Core\Notification\PostSubscriptions;

use Minds\Core\Notification\PostSubscriptions\PostSubscription;
use Minds\Core\Notification\PostSubscriptions\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    protected $repository;

    function let(
        Repository $repository
    ) {
        $this->beConstructedWith($repository);

        $this->repository = $repository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Notification\PostSubscriptions\Manager');
    }

    function it_should_set_entity_guid()
    {
        $this
            ->setEntityGuid(5000)
            ->shouldReturn($this);
    }

    function it_should_set_user_guid()
    {
        $this
            ->setUserGuid(1000)
            ->shouldReturn($this);
    }

    function it_should_get()
    {
        $postSubscription = new PostSubscription();

        $this->repository->get(5000, 1000)
            ->shouldBeCalled()
            ->willReturn($postSubscription);

        $this
            ->setEntityGuid(5000)
            ->setUserGUid(1000)
            ->get()
            ->shouldReturn($postSubscription);
    }

    function it_should_get_a_non_existing()
    {
        $this->repository->get(5000, 1000)
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->setEntityGuid(5000)
            ->setUserGUid(1000)
            ->get()
            ->shouldBeAnInstanceOf(PostSubscription::class);
    }

    function it_should_follow()
    {
        $this->repository->add(Argument::type(PostSubscription::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setEntityGuid(5000)
            ->setUserGUid(1000)
            ->follow()
            ->shouldReturn(true);
    }

    function it_should_follow_without_forcing()
    {
        $this->repository->update(Argument::type(PostSubscription::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setEntityGuid(5000)
            ->setUserGUid(1000)
            ->follow(false)
            ->shouldReturn(true);
    }

    function it_should_unfollow()
    {
        $this->repository->add(Argument::type(PostSubscription::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setEntityGuid(5000)
            ->setUserGUid(1000)
            ->unfollow()
            ->shouldReturn(true);
    }

    function it_should_unsubscribe()
    {
        $this->repository->delete(Argument::type(PostSubscription::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setEntityGuid(5000)
            ->setUserGUid(1000)
            ->unsubscribe()
            ->shouldReturn(true);
    }
}
