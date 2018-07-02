<?php

namespace Spec\Minds\Core\Comments;

use Minds\Core\Comments\Comment;
use Minds\Core\Comments\Delegates\CountCache;
use Minds\Core\Comments\Delegates\CreateEventDispatcher;
use Minds\Core\Comments\Delegates\Metrics;
use Minds\Core\Comments\Delegates\ThreadNotifications;
use Minds\Core\Comments\Legacy\Repository as LegacyRepository;
use Minds\Core\Comments\Repository;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Luid;
use Minds\Core\Security\ACL;
use Minds\Entities\Entity;
use Minds\Entities\User;
use Minds\Exceptions\BlockedUserException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var LegacyRepository */
    protected $legacyRepository;

    /** @var ACL */
    protected $acl;

    /** @var Metrics */
    protected $metrics;

    /** @var ThreadNotifications */
    protected $threadNotifications;

    /** @var CreateEventDispatcher */
    protected $createEventDispatcher;

    /** @var CountCache */
    protected $countCache;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    function let(
        Repository $repository,
        LegacyRepository $legacyRepository,
        ACL $acl,
        Metrics $metrics,
        ThreadNotifications $threadNotifications,
        CreateEventDispatcher $createEventDispatcher,
        CountCache $countCache,
        EntitiesBuilder $entitiesBuilder
    ) {
        $this->beConstructedWith(
            $repository,
            $legacyRepository,
            $acl,
            $metrics,
            $threadNotifications,
            $createEventDispatcher,
            $countCache,
            $entitiesBuilder
        );

        $this->repository = $repository;
        $this->legacyRepository = $legacyRepository;
        $this->acl = $acl;
        $this->metrics = $metrics;
        $this->threadNotifications = $threadNotifications;
        $this->createEventDispatcher = $createEventDispatcher;
        $this->countCache = $countCache;
        $this->entitiesBuilder = $entitiesBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Manager');
    }

    function it_should_add(
        Comment $comment,
        Entity $entity,
        User $owner
    )
    {
        $comment->getOwnerEntity(true)
            ->shouldBeCalled()
            ->willReturn($owner);

        $comment->getOwnerGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->entitiesBuilder->single(5000)
            ->shouldBeCalled()
            ->willReturn($entity);

        $this->acl->interact($entity, $owner)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->repository->add($comment)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->threadNotifications->notify($comment)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->threadNotifications->subscribeOwner($comment)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->metrics->push($comment)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->createEventDispatcher->dispatch($comment)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->countCache->destroy($comment)
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->add($comment)
            ->shouldReturn(true);
    }

    function it_should_throw_if_blocked_user_during_add(
        Comment $comment,
        Entity $entity,
        User $owner
    )
    {
        $comment->getOwnerEntity(true)
            ->shouldBeCalled()
            ->willReturn($owner);

        $comment->getOwnerGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->entitiesBuilder->single(5000)
            ->shouldBeCalled()
            ->willReturn($entity);

        $this->acl->interact($entity, $owner)
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->shouldThrow(BlockedUserException::class)
            ->duringAdd($comment);
    }

    function it_should_update(
        Comment $comment
    )
    {
        $comment->getDirtyAttributes()
            ->shouldBeCalled()
            ->willReturn(['body']);

        $this->repository->update($comment, ['body'])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->update($comment)
            ->shouldReturn(true);
    }

    function it_should_delete(
        Comment $comment
    )
    {
        $this->repository->delete($comment)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->countCache->destroy($comment)
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->delete($comment)
            ->shouldReturn(true);
    }

    function it_should_get_by_luid(
        Comment $comment
    )
    {
        $this->repository->get('5000', '0', '6000')
            ->shouldBeCalled()
            ->willReturn($comment);

        $this
            ->getByLuid(
                (new Luid())
                    ->setType('comment')
                    ->setEntityGuid(5000)
                    ->setParentGuid(0)
                    ->setGuid(6000)
                    ->build()
            )
            ->shouldReturn($comment);
    }

    function it_should_fallback_to_legacy_if_throws_because_old_guid_during_get_by_luid(
        Comment $comment
    )
    {
        $this->repository->get(Argument::cetera())
            ->shouldNotBeCalled();

        $this->legacyRepository->getByGuid('100000000000000000')
            ->shouldBeCalled()
            ->willReturn($comment);

        $this
            ->getByLuid('100000000000000000')
            ->shouldReturn($comment);
    }

    function it_should_return_null_if_throws_during_get_by_luid()
    {
        $this->repository->get(Argument::cetera())
            ->shouldNotBeCalled();

        $this->legacyRepository->getByGuid(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->getByLuid('~123q11˜ñn')
            ->shouldReturn(null);
    }

    function it_should_count()
    {
        $this->repository->count(5000, 0)
            ->shouldBeCalled()
            ->willReturn(3);

        $this
            ->count(5000, 0)
            ->shouldReturn(3);
    }

    function it_should_return_zero_if_throws_during_count()
    {
        $this->repository->count(5000, 0)
            ->willThrow(new \Exception());

        $this
            ->count(5000, 0)
            ->shouldReturn(0);
    }
}
