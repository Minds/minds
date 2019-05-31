<?php

namespace Spec\Minds\Core\Analytics\Views;

use Minds\Core\Analytics\Views\Manager;
use Minds\Core\Analytics\Views\Repository;
use Minds\Core\Analytics\Views\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    function let(
        Repository $repository
    )
    {
        $this->beConstructedWith($repository);
        $this->repository = $repository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_record(
        View $view
    )
    {
        $view->setYear(null)
            ->shouldBeCalled()
            ->willReturn($view);

        $view->setMonth(null)
            ->shouldBeCalled()
            ->willReturn($view);

        $view->setDay(null)
            ->shouldBeCalled()
            ->willReturn($view);

        $view->setUuid(null)
            ->shouldBeCalled()
            ->willReturn($view);

        $view->setTimestamp(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn($view);

        $this->repository->add($view)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->record($view)
            ->shouldReturn(true);
    }
}
