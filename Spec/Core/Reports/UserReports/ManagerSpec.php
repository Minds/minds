<?php

namespace Spec\Minds\Core\Reports\UserReports;

use Minds\Core\Reports\UserReports\Manager;
use Minds\Core\Reports\UserReports\Repository;
use Minds\Core\Reports\UserReports\ElasticRepository;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Reports\UserReports\Delegates;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $elasticRepository;
    private $notificationDelegate;

    function let(
        Repository $repository,
        ElasticRepository $elasticRepository,
        Delegates\NotificationDelegate $notificationDelegate
    )
    {
        $this->beConstructedWith($repository, $elasticRepository, $notificationDelegate);
        $this->repository = $repository;
        $this->elasticRepository = $elasticRepository;
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

        $this->elasticRepository->add(Argument::type(UserReport::class))
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->notificationDelegate->onAction(Argument::type(UserReport::class))
            ->shouldBeCalled();

        $userReport = new UserReport;
        $this->add($userReport)
            ->shouldReturn(true);
    }
}
