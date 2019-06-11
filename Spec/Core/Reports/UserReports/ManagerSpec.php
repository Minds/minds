<?php

namespace Spec\Minds\Core\Reports\UserReports;

use Minds\Core\Reports\UserReports\Manager;
use Minds\Core\Reports\UserReports\Repository;
use Minds\Core\Reports\UserReports\ElasticRepository;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Reports\UserReports\Delegates;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Manager as ReportsManager;
use Minds\Entities\Activity;
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

    function it_should_not_double_report_marked_nsfw_repository(Report $report)
    {
        $entity = new Activity();
        $entity->setNsfw([ 3 ]);

        $report->getReasonCode()
            ->willReturn(2);
        
        $report->getSubReasonCode()
            ->willReturn(3);
            
        $report->getEntity()
            ->willReturn($entity);

        $report->getState()
            ->willReturn('reported');

        $this->repository->add(Argument::type(UserReport::class))
            ->shouldNotBeCalled();

        $this->reportsManager->getLatestReport(Argument::type(Report::class))
            ->shouldBeCalled()
            ->willReturn($report);
        
        $this->notificationDelegate->onAction(Argument::type(UserReport::class))
            ->shouldNotBeCalled();

        $userReport = new UserReport;
        $userReport->setReport($report);
        $this->add($userReport)
            ->shouldReturn(true);
    }
}
