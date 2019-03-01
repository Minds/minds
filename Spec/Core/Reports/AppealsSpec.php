<?php

namespace Spec\Minds\Core\Reports;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;

class AppealsSpec extends ObjectBehavior
{
    public $_repository;
    public $_actions;

    function let(
        Core\Reports\PreFeb2019Repository $repository,
        Core\Reports\Actions $actions
    )
    {
        $this->_repository = $repository;
        Di::_()->bind('Reports\Repository', function($di) use ($repository) {
            return $repository->getWrappedObject();
        });

        $this->_actions = $actions;
        Di::_()->bind('Reports\Actions', function($di) use ($actions) {
            return $actions->getWrappedObject();
        });
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Reports\Appeals');
    }

    // appeal()

    function it_should_appeal_a_report(
        Entities\Report $report
    )
    {
        $report->getOwnerGuid()->willReturn(1000);
        $report->getState()->willReturn('actioned');

        $this->_repository->getRow(5000)
            ->shouldBeCalled()
            ->willReturn($report);

        $this->_repository->update(5000, [
            'appeal_note' => 'phpspec',
            'state' => 'appealed'
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->appeal(5000, 1000, 'phpspec')
            ->shouldReturn(true);
    }

    function it_should_not_appeal_if_no_guid(
        Entities\Report $report
    )
    {
        $report->getOwnerGuid()->willReturn(1000);
        $report->getState()->willReturn('actioned');

        $this->_repository->getRow(Argument::any())
            ->shouldNotBeCalled();

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringAppeal(null, 1000, 'phpspec');
    }

    function it_should_not_appeal_if_no_user(
        Entities\Report $report
    )
    {
        $report->getOwnerGuid()->willReturn(1000);
        $report->getState()->willReturn('actioned');

        $this->_repository->getRow(Argument::any())
            ->shouldNotBeCalled();

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringAppeal(5000, null, 'phpspec');
    }

    function it_should_not_appeal_if_no_report(
        Entities\Report $report
    )
    {
        $report->getOwnerGuid()->willReturn(1000);
        $report->getState()->willReturn('actioned');

        $this->_repository->getRow(5404)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringAppeal(5404, 1000, 'phpspec');
    }

    function it_should_not_appeal_if_report_is_not_users(
        Entities\Report $report
    )
    {
        $report->getOwnerGuid()->willReturn(1001);
        $report->getState()->willReturn('actioned');

        $this->_repository->getRow(5401)
            ->shouldBeCalled()
            ->willReturn($report);

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringAppeal(5401, 1000, 'phpspec');
    }

    function it_should_not_appeal_if_report_is_other_than_actioned(
        Entities\Report $report
    )
    {
        $report->getOwnerGuid()->willReturn(1000);
        $report->getState()->willReturn('review');

        $this->_repository->getRow(5000)
            ->shouldBeCalled()
            ->willReturn($report);

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringAppeal(5000, 1000, 'phpspec');
    }

    // approve()

    function it_should_approve_an_appeal(
        Entities\Report $report
    )
    {
        $report->getState()->willReturn('appealed');

        $this->_repository->getRow(5000)
            ->shouldBeCalled()
            ->willReturn($report);

        $this->_actions->undo($report)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->_repository->update(5000, [
            'state' => 'appeal_approved'
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->approve(5000)
            ->shouldReturn(true);
    }

    function it_should_not_approve_an_appeal_if_no_guid(
        Entities\Report $report
    )
    {
        $report->getState()->willReturn('appealed');

        $this->_repository->getRow(Argument::any())
            ->shouldNotBeCalled();

        $this->_actions->undo($report)
            ->shouldNotBeCalled();

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringApprove(null);
    }

    function it_should_not_approve_an_appeal_if_no_report(
        Entities\Report $report
    )
    {
        $report->getState()->willReturn('appealed');

        $this->_repository->getRow(5404)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->_actions->undo($report)
            ->shouldNotBeCalled();

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringApprove(5404);
    }

    function it_should_not_approve_an_appeal_if_no_report_is_other_than_appealed(
        Entities\Report $report
    )
    {
        $report->getState()->willReturn('review');

        $this->_repository->getRow(5000)
            ->shouldBeCalled()
            ->willReturn($report);

        $this->_actions->undo($report)
            ->shouldNotBeCalled();

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringApprove(5000);
    }

    // reject()

    function it_should_reject_an_appeal(
        Entities\Report $report
    )
    {
        $report->getState()->willReturn('appealed');

        $this->_repository->getRow(5000)
            ->shouldBeCalled()
            ->willReturn($report);

        $this->_actions->undo($report)
            ->shouldNotBeCalled();

        $this->_repository->update(5000, [
            'state' => 'appeal_rejected'
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->reject(5000)
            ->shouldReturn(true);
    }

    function it_should_not_reject_an_appeal_if_no_guid(
        Entities\Report $report
    )
    {
        $report->getState()->willReturn('appealed');

        $this->_repository->getRow(Argument::any())
            ->shouldNotBeCalled();

        $this->_actions->undo($report)
            ->shouldNotBeCalled();

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringReject(null);
    }

    function it_should_not_reject_an_appeal_if_no_report(
        Entities\Report $report
    )
    {
        $report->getState()->willReturn('appealed');

        $this->_repository->getRow(5404)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->_actions->undo($report)
            ->shouldNotBeCalled();

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringReject(5404);
    }

    function it_should_not_reject_an_appeal_if_no_report_is_other_than_appealed(
        Entities\Report $report
    )
    {
        $report->getState()->willReturn('review');

        $this->_repository->getRow(5000)
            ->shouldBeCalled()
            ->willReturn($report);

        $this->_actions->undo($report)
            ->shouldNotBeCalled();

        $this->_repository->update(Argument::cetera())
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringReject(5000);
    }
}
