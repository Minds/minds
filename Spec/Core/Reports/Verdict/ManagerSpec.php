<?php

namespace Spec\Minds\Core\Reports\Verdict;

use Minds\Core\Reports\Verdict\Manager;
use Minds\Core\Reports\Verdict\Repository;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Verdict\Delegates;
use Minds\Core\Reports\Report;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class ManagerSpec extends ObjectBehavior
{
    private $repository;
    private $actionDelegate;
    private $reverseDelegate;
    private $notificationDelegate;
    private $releaseSummonsesDelegate;
    private $metricsDelegate;

    function let(
        Repository $repository,
        Delegates\ActionDelegate $actionDelegate,
        Delegates\ReverseActionDelegate $reverseDelegate,
        Delegates\NotificationDelegate $notificationDelegate,
        Delegates\ReleaseSummonsesDelegate $releaseSummonsesDelegate,
        Delegates\MetricsDelegate $metricsDelegate
    )
    {
        $this->beConstructedWith(
            $repository,
            $actionDelegate,
            $reverseDelegate,
            $notificationDelegate,
            $releaseSummonsesDelegate,
            $metricsDelegate);

        $this->repository = $repository;
        $this->actionDelegate = $actionDelegate;
        $this->reverseDelegate = $reverseDelegate;
        $this->notificationDelegate = $notificationDelegate;
        $this->releaseSummonsesDelegate = $releaseSummonsesDelegate;
        $this->metricsDelegate = $metricsDelegate;

    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_add_verdict_to_repository(Verdict $verdict, Report $report)
    {
        $verdict->isAppeal()
            ->willReturn(false);

        $verdict->isUpheld()
            ->willReturn(false);

        $verdict->getReport()
            ->willReturn($report);

        $this->repository->add($verdict)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->actionDelegate->onAction($verdict)
            ->shouldBeCalled();

        $this->metricsDelegate->onCast($verdict)
            ->shouldBeCalled();

        $this->notificationDelegate->onAction($verdict)
            ->shouldBeCalled();

        $this->releaseSummonsesDelegate->onCast($verdict)
            ->shouldBeCalled();

        $this->cast($verdict->getWrappedObject());
    }

    function it_should_return_a_single_verdict_object_from_repository()
    {
        $ts = microtime(true);
        $report = new Report();

        $report->setInitialJuryDecisions($decisions = [
            (new Decision)
                ->setAction('overturned'),
            (new Decision)
                ->setAction('explicit'),
        ]);

        $this->repository->get(123)
            ->shouldBeCalled()
            ->willReturn(
                (new Verdict())
                    ->setTimestamp($ts)
                    ->setAction(null)
                    ->setReport($report)
            );

        $verdict = $this->get(123);

        $verdict->getReport()
            ->shouldBe($report);
        $verdict->getDecisions()
            ->shouldBe($decisions);
        $verdict->getTimestamp()
            ->shouldBe($ts);
        $verdict->getAction()
            ->shouldBe(null);
    }

    function it_should_return_the_verdict_action_as_upheld_for_initial_jury(Verdict $verdict, Report $report)
    {
        $report->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->getReport()
            ->willReturn($report);

        $verdict->getDecisions()
            ->shouldBeCalled()
            ->willReturn([
                (new Decision)
                    ->setUphold(true),
            ]);

        $this->isUpheld($verdict)
            ->shouldBe(true);
    }

    function it_should_return_the_appeal_verdict_action_as_upheld()
    {
        $report = new Report();
        $report->setAppeal(true);

        $report->setAppealJuryDecisions([
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
            ]);

        $verdict = new Verdict;
        $verdict->setReport($report);

        $this->isUpheld($verdict)
            ->shouldBe(true);
    }

    function it_should_return_the_appeal_verdict_action_as_overturned()
    {
        $report = new Report();
        $report->setAppeal(true);

        $report->setAppealJuryDecisions([
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
                (new Decision)
                    ->setUphold(false),
            ]);

        $verdict = new Verdict;
        $verdict->setReport($report);

        $this->isUpheld($verdict)
            ->shouldBe(false);
    }

    function it_should_return_the_appeal_verdict_action_as_null()
    {
        $report = new Report();
        $report->setAppeal(true);

        $report->setAppealJuryDecisions([
                (new Decision)
                    ->setUphold(true),
                (new Decision)
                    ->setUphold(false),
            ]);

        $verdict = new Verdict;
        $verdict->setReport($report);

        $this->isUpheld($verdict)
            ->shouldBe(null);
    }

    function it_should_decide_verdict_from_a_report(Report $report)
    {
        $report->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $report->getInitialJuryDecisions()
            ->shouldBeCalled()
            ->willReturn([
                (new Decision)
                    ->setAction('2.2'),
            ]);
        
        $report->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn('urn:activity:123');

        $this->repository->add(Argument::type(Verdict::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->decideFromReport($report)
            ->shouldBe(true);
    }

}
