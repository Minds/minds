<?php

namespace Spec\Minds\Core\Reports\UserReports;

use Minds\Core\Reports\UserReports\Manager;
use Minds\Core\Reports\UserReports\Repository;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Reports\UserReports\Delegates;
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

    function it_should_add_to_repository()
    {
        $this->repository->add(Argument::type(UserReport::class))
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->notificationDelegate->onAction(Argument::type(UserReport::class))
            ->shouldBeCalled();

        $userReport = new UserReport;
        $this->add($userReport)
            ->shouldReturn(true);
    }
}
