<?php

namespace Spec\Minds\Core\Reports\UserReports;

use Minds\Core\Reports\UserReports\Manager;
use Minds\Core\Reports\UserReports\Repository;
use Minds\Core\Reports\UserReports\ElasticRepository;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Reports\UserReports\Delegates;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Manager as ReportsManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $elasticRepository;
    private $notificationDelegate;
    private $reportsManager;

    function let(
        Repository $repository,
        ElasticRepository $elasticRepository,
        Delegates\NotificationDelegate $notificationDelegate,
        ReportsManager $reportsManager
    )
    {
        $this->beConstructedWith($repository, $elasticRepository, $notificationDelegate, $reportsManager);
        $this->repository = $repository;
        $this->elasticRepository = $elasticRepository;
        $this->notificationDelegate = $notificationDelegate;
        $this->reportsManager = $reportsManager;
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

        $this->reportsManager->getLatestReport(Argument::type(Report::class))
            ->shouldBeCalled()
            ->willReturn(new Report);
        
        $this->notificationDelegate->onAction(Argument::type(UserReport::class))
            ->shouldBeCalled();

        $userReport = new UserReport;
        $userReport->setReport(new Report);
        $this->add($userReport)
            ->shouldReturn(true);
    }
}
