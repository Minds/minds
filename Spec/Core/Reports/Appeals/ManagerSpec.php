<?php

namespace Spec\Minds\Core\Reports\Appeals;

use Minds\Core\Reports\Appeals\Manager;
use Minds\Core\Reports\Appeals\Repository;
use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Appeals\Delegates;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $notificationDelegate;

    function let(
        Repository $repository,
        Delegates\NotificationDelegate $notificationDelegate
    )
    {
        $this->beConstructedWith($repository, $notificationDelegate);
        $this->repository = $repository;
        $this->notificationDelegate = $notificationDelegate;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_add_appeal_to_repository(Appeal $appeal)
    {
        $this->repository->add($appeal)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->notificationDelegate->onAction($appeal)
            ->shouldBeCalled();

        $this->appeal($appeal)
            ->shouldBe(true);
    }

}
