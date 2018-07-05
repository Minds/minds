<?php

namespace Spec\Minds\Core\Reports;

use Minds\Core;
use Minds\Entities\Activity;
use Minds\Entities\EntitiesFactory;
use Minds\Entities\Report;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionsSpec extends ObjectBehavior
{
    /** @var Core\Entities\Actions\Save */
    protected $saveAction;

    /** @var Core\Reports\Repository */
    protected $repository;

    /** @var EntitiesFactory */
    protected $entitiesFactory;

    /** @var Core\Events\EventsDispatcher */
    protected $dispatcher;

    function let(
        Core\Entities\Actions\Save $saveAction,
        Core\Reports\Repository $repository,
        EntitiesFactory $factory,
        Core\Events\EventsDispatcher $dispatcher
    ) {
        $this->beConstructedWith($saveAction, $repository, $factory, $dispatcher);
        $this->saveAction = $saveAction;
        $this->repository = $repository;
        $this->entitiesFactory = $factory;
        $this->dispatcher = $dispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Reports\Actions');
    }

    function it_should_archive_a_report_and_return_true()
    {
        $this->repository->update(5000, ['state' => 'archived'])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->archive(5000)->shouldReturn(true);
    }

    function it_should_archive_a_report_and_return_false()
    {
        $this->repository->update(5000, ['state' => 'archived'])
            ->shouldBeCalled()
            ->willReturn(false);

        $this->archive(5000)->shouldReturn(false);
    }

    function it_should_mark_as_explicit(Report $report, Activity $activity)
    {
        $this->repository->getRow('1234')
            ->shouldBeCalled()
            ->willReturn($report);

        $report->setReason('test')
            ->shouldBeCalled();

        $luid = base64_encode('testtesttest');

        $report->getEntityLuid()
            ->shouldBeCalled()
            ->willReturn($luid);

        $this->entitiesFactory->build($luid)
            ->shouldBeCalled()
            ->willReturn($activity);

        $activity->setMature(true)
            ->shouldBeCalled();

        $activity->get('custom_data')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->saveAction->setEntity($activity)
            ->shouldBeCalled()
            ->willReturn($this->saveAction);

        $this->saveAction->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $activity->get('attachment_guid')
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->get('entity_guid')
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->get('owner_guid')
            ->shouldBeCalled()
            ->willReturn('5678');

        $report->getReason()
            ->shouldBeCalled()
            ->willReturn('test');

        $this->dispatcher->trigger('notification', 'all', Argument::any())
            ->shouldBeCalled();

        $this->repository->update('1234', ['state' => 'actioned', 'action' => 'explicit', 'reason' => 'test'])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->markAsExplicit('1234', 'test')->shouldReturn(true);
    }

    function it_should_mark_as_spam(Report $report, Activity $activity)
    {
        $this->repository->getRow('1234')
            ->shouldBeCalled()
            ->willReturn($report);

        $report->setReason('test')
            ->shouldBeCalled();

        $luid = base64_encode('testtesttest');

        $report->getEntityLuid()
            ->shouldBeCalled()
            ->willReturn($luid);

        $this->entitiesFactory->build($luid)
            ->shouldBeCalled()
            ->willReturn($activity);

        $activity->setSpam(true)
            ->shouldBeCalled();

        $this->saveAction->setEntity($activity)
            ->shouldBeCalled()
            ->willReturn($this->saveAction);

        $this->saveAction->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $activity->get('attachment_guid')
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->get('entity_guid')
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->get('owner_guid')
            ->shouldBeCalled()
            ->willReturn('5678');

        $report->getReason()
            ->shouldBeCalled()
            ->willReturn('test');

        $this->dispatcher->trigger('notification', 'all', Argument::any())
            ->shouldBeCalled();

        $this->repository->update('1234', ['state' => 'actioned', 'action' => 'spam', 'reason' => 'test'])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->markAsSpam('1234', 'test')->shouldReturn(true);
    }

    function it_should_delete_the_entity(Report $report, Activity $activity)
    {
        $this->repository->getRow('1234')
            ->shouldBeCalled()
            ->willReturn($report);

        $report->setReason('test')
            ->shouldBeCalled();

        $luid = base64_encode('testtesttest');

        $report->getEntityLuid()
            ->shouldBeCalled()
            ->willReturn($luid);

        $this->entitiesFactory->build($luid)
            ->shouldBeCalled()
            ->willReturn($activity);

        $activity->setDeleted(true)
            ->shouldBeCalled();

        $this->saveAction->setEntity($activity)
            ->shouldBeCalled()
            ->willReturn($this->saveAction);

        $this->saveAction->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $activity->get('attachment_guid')
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->get('entity_guid')
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->get('owner_guid')
            ->shouldBeCalled()
            ->willReturn('5678');

        $report->getReason()
            ->shouldBeCalled()
            ->willReturn('test');

        $this->dispatcher->trigger('notification', 'all', Argument::any())
            ->shouldBeCalled();

        $this->repository->update('1234', ['state' => 'actioned', 'action' => 'delete', 'reason' => 'test'])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delete('1234', 'test')->shouldReturn(true);
    }

    function it_should_undo_a_previous_action(Report $report, Activity $activity)
    {

        $report->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $luid = base64_encode('testtesttest');

        $report->getEntityLuid()
            ->shouldBeCalled()
            ->willReturn($luid);

        $this->entitiesFactory->build($luid)
            ->shouldBeCalled()
            ->willReturn($activity);

        $activity->setMature(false)
            ->shouldBeCalled();

        $this->saveAction->setEntity($activity)
            ->shouldBeCalled()
            ->willReturn($this->saveAction);

        $this->saveAction->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $activity->get('attachment_guid')
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->get('entity_guid')
            ->shouldBeCalled()
            ->willReturn(null);

        $activity->get('custom_data')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->undo($report)->shouldReturn(true);
    }
    // TODO: move helper functions (flag, etc) to another mockable class
}

