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

    function it_should_return_the_verdict_action_as_explicit(Verdict $verdict)
    {
        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->getDecisions()
            ->shouldBeCalled()
            ->willReturn([
                (new Decision)
                    ->setAction('explicit'),
            ]);

        $this->getAction($verdict)
            ->shouldBe('explicit');
    }

    function it_should_return_the_appeal_verdict_action_as_explicit(Verdict $verdict)
    {
        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getInitialJuryAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $verdict->getDecisions()
            ->shouldBeCalled()
            ->willReturn([
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('overturned'),
                (new Decision)
                    ->setAction('overturned'),
            ]);

        $this->getAction($verdict)
            ->shouldBe('explicit');
    }

    function it_should_return_the_appeal_verdict_action_as_overturned(Verdict $verdict)
    {
        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getInitialJuryAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $verdict->getDecisions()
            ->shouldBeCalled()
            ->willReturn([
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('explicit'),
                (new Decision)
                    ->setAction('overturned'),
                (new Decision)
                    ->setAction('overturned'),
                (new Decision)
                    ->setAction('overturned'),
                (new Decision)
                    ->setAction('overturned'),
                (new Decision)
                    ->setAction('overturned'),
            ]);

        $this->getAction($verdict)
            ->shouldBe('overturned');
    }

}
