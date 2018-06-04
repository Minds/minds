<?php

namespace Spec\Minds\Core\Comments\Votes;

use Minds\Core\Comments\Comment;
use Minds\Core\Comments\Legacy\Repository as LegacyCommentsRepository;
use Minds\Core\Comments\Votes\Repository;
use Minds\Core\Votes\Vote;
use Minds\Entities\Entity;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var LegacyCommentsRepository */
    protected $legacyRepository;

    function let(
        Repository $repository,
        LegacyCommentsRepository $legacyRepository
    ) {
        $this->beConstructedWith($repository, $legacyRepository);

        $this->repository = $repository;
        $this->legacyRepository = $legacyRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Votes\Manager');
    }

    function it_has_vote_up(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $comment->getVotesUp()
            ->shouldBeCalled()
            ->willReturn([ 1000, 1001 ]);

        $comment->getVotesDown()
            ->shouldNotBeCalled();

        $actor->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('up');

        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this
            ->setVote($vote)
            ->has()
            ->shouldReturn(true);
    }

    function it_does_not_have_vote_up(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $comment->getVotesUp()
            ->shouldBeCalled()
            ->willReturn([ 1000, 1001 ]);

        $comment->getVotesDown()
            ->shouldNotBeCalled();

        $actor->get('guid')
            ->shouldBeCalled()
            ->willReturn(1003);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('up');

        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this
            ->setVote($vote)
            ->has()
            ->shouldReturn(false);
    }

    function it_has_vote_down(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $comment->getVotesDown()
            ->shouldBeCalled()
            ->willReturn([ 1000, 1001 ]);

        $comment->getVotesUp()
            ->shouldNotBeCalled();

        $actor->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('down');

        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this
            ->setVote($vote)
            ->has()
            ->shouldReturn(true);
    }

    function it_does_not_have_vote_down(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $comment->getVotesDown()
            ->shouldBeCalled()
            ->willReturn([ 1000, 1001 ]);

        $comment->getVotesUp()
            ->shouldNotBeCalled();

        $actor->get('guid')
            ->shouldBeCalled()
            ->willReturn(1003);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('down');

        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this
            ->setVote($vote)
            ->has()
            ->shouldReturn(false);
    }

    function it_should_cast(
        Vote $vote,
        Entity $entity
    ) {
        $entity->get('guid')->willReturn('5000');

        $vote->getEntity()->willReturn($entity);

        $this->legacyRepository->isLegacy('5000');

        $this->repository->add($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setVote($vote)
            ->cast()
            ->shouldReturn(true);
    }

    function it_should_cancel(
        Vote $vote,
        Entity $entity
    ) {
        $entity->get('guid')->willReturn('5000');

        $vote->getEntity()->willReturn($entity);

        $this->legacyRepository->isLegacy('5000');

        $this->repository->delete($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setVote($vote)
            ->cancel()
            ->shouldReturn(true);
    }
}
