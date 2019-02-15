<?php

namespace Spec\Minds\Core\Suggestions\Pass;

use Minds\Core\Suggestions\Pass\Manager;
use Minds\Core\Suggestions\Pass\Repository;
use Minds\Core\Suggestions\Pass\Pass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    private $repository;

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function let(Repository $repository)
    {
        $this->beConstructedWith($repository);
        $this->repository = $repository;
    }

    function it_should_add_pass_to_the_repository()
    {
        $pass = new Pass;
        
        $this->repository->add($pass)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($pass)
            ->shouldReturn(true);
    }

}
