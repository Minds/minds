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
    private $notificationDelegate;

    function let(
        Repository $repository,
        Delegates\ActionDelegate $actionDelegate,
        Delegates\NotificationDelegate $notificationDelegate
    )
    {
        $this->beConstructedWith($repository, $actionDelegate, $notificationDelegate);
        $this->repository = $repository;
        $this->actionDelegate = $actionDelegate;
        $this->notificationDelegate = $notificationDelegate;

    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_add_verdict_to_repository(Verdict $verdict)
    {
        $this->repository->add($verdict)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->actionDelegate->onAction($verdict)
            ->shouldBeCalled();

        $this->notificationDelegate->onAction($verdict)
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

    function it_should_return_the_verdict_action_as_explicit(Verdict $verdict)
    {
        $report = new Report();
        $verdict->getReport()
            ->willReturn($report);

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->getDecisions()
            ->shouldBeCalled()
            ->willReturn([
                (new Decision)
                    ->setAction('2.2'),
            ]);

        $this->getAction($verdict)
            ->shouldBe('2.2');
    }

    function it_should_return_the_appeal_verdict_action_as_explicit()
    {
        $report = new Report();
        $report->setAppeal(true);

        $report->setAppealJuryDecisions([
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('overturn'),
                (new Decision)
                    ->setAction('overturn'),
            ]);

        $verdict = new Verdict;
        $verdict->setReport($report);

        $this->getAction($verdict)
            ->shouldBe('uphold');
    }

    function it_should_return_the_appeal_verdict_action_as_overturned()
    {
        $report = new Report();
        $report->setAppeal(true);

        $report->setAppealJuryDecisions([
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('overturn'),
                (new Decision)
                    ->setAction('overturn'),
                (new Decision)
                    ->setAction('overturn'),
                (new Decision)
                    ->setAction('overturn'),
                (new Decision)
                    ->setAction('overturn'),
            ]);

        $verdict = new Verdict;
        $verdict->setReport($report);

        $this->getAction($verdict)
            ->shouldBe('overturn');
    }

    function it_should_return_the_appeal_verdict_action_as_null()
    {
        $report = new Report();
        $report->setAppeal(true);

        $report->setAppealJuryDecisions([
                (new Decision)
                    ->setAction('uphold'),
                (new Decision)
                    ->setAction('overturn'),
            ]);

        $verdict = new Verdict;
        $verdict->setReport($report);

        $this->getAction($verdict)
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
        
        $report->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(123);

        $this->repository->add(Argument::type(Verdict::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->decideFromReport($report)
            ->shouldBe(true);
    }

}
