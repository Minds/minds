<?php

namespace Spec\Minds\Core\Reports\Verdict\Delegates;

use Exception;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Summons\Manager;
use Minds\Core\Reports\Verdict\Delegates\ReleaseSummonsesDelegate;
use Minds\Core\Reports\Verdict\Verdict;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseSummonsesDelegateSpec extends ObjectBehavior
{
    /** @var Manager */
    protected $summonsManager;

    function let(
        Manager $summonsManager
    )
    {
        $this->beConstructedWith($summonsManager);
        $this->summonsManager = $summonsManager;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ReleaseSummonsesDelegate::class);
    }

    function it_should_release_initial_jury_on_cast(Verdict $verdict, Report $report)
    {
        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);

        $report->getUrn()
            ->shouldBeCalled()
            ->willReturn('urn:report:phpspec');

        $this->summonsManager->release('urn:report:phpspec', 'initial_jury')
            ->shouldBeCalled();

        $this
            ->shouldNotThrow()
            ->duringOnCast($verdict);
    }

    function it_should_release_appeal_jury_on_cast(Verdict $verdict, Report $report)
    {
        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);

        $report->getUrn()
            ->shouldBeCalled()
            ->willReturn('urn:report:phpspec');

        $this->summonsManager->release('urn:report:phpspec', 'appeal_jury')
            ->shouldBeCalled();

        $this
            ->shouldNotThrow()
            ->duringOnCast($verdict);
    }
}
