<?php

namespace Spec\Minds\Core\Helpdesk\Question\Votes;

use Minds\Core\Helpdesk\Question\Question;
use Minds\Core\Helpdesk\Question\Repository;
use Minds\Core\Helpdesk\Question\Votes\Manager;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    private $repository;

    function let(Repository $repository)
    {
        $this->beConstructedWith($repository);

        $this->repository = $repository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_cast_a_vote(User $user)
    {
        $this->repository->update(Argument::type(Question::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $user->getGUID()->willReturn(123);
        $this->setUser($user);

        $question = new Question();
        $question->setUuid('uuid1');
        $this->setQuestion($question);

        $this->setDirection('up');

        $this->vote()->shouldReturn(true);
    }

    function it_should_remove_a_vote(User $user)
    {
        $this->repository->update(Argument::type(Question::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $user->getGUID()->willReturn(123);
        $this->setUser($user);

        $question = new Question();
        $question->setUuid('uuid1');
        $this->setQuestion($question);

        $this->setDirection('down');

        $this->delete()->shouldReturn(true);
    }

}
