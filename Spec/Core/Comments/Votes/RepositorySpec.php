<?php

namespace Spec\Minds\Core\Comments\Votes;

use Minds\Core\Comments\Comment;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Votes\Vote;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $cql;

    function let(
        Client $cql
    ) {
        $this->beConstructedWith($cql);

        $this->cql = $cql;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Comments\Votes\Repository');
    }

    function it_should_add(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('up');

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $cql = $prepared->build()['string'];

            return stripos($cql, 'update comments set votes_up = votes_up + ?') !== false;
        }));

        $this
            ->add($vote);
    }

    function it_should_delete(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('down');

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $cql = $prepared->build()['string'];

            return stripos($cql, 'update comments set votes_down = votes_down - ?') !== false;
        }));

        $this
            ->delete($vote);
    }
}
