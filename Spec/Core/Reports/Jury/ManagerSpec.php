<?php

namespace Spec\Minds\Core\Reports\Jury;

use Minds\Core\Reports\Jury\Manager;
use Minds\Core\Reports\Jury\Repository;
use Minds\Core\Reports\Jury\Decision;
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

    function is_should_cast_a_jury_decision(Decision $decision)
    {
        $this->repository->add($decision)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cast($decision)
            ->shouldBe(true);
    }

}
