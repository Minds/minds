<?php

namespace Spec\Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Reports\Verdict\Delegates\NotificationDelegate;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Reports\Report;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\Entity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotificationDelegateSpec extends ObjectBehavior
{
    /** @var EventsDispatcher $dispatcher */
    private $dispatcher;

    /** @var EntitiesBuilder $entitiesBuilder */
    private $entitiesBuilder;

    function let(EventsDispatcher $dispatcher, EntitiesBuilder $entitiesBuilder)
    {
        $this->beConstructedWith($dispatcher, $entitiesBuilder);
        $this->dispatcher = $dispatcher;
        $this->entitiesBuilder = $entitiesBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NotificationDelegate::class);
    }

    function it_should_send_a_marked_as_nsfw_notification(Verdict $verdict, Report $report, Entity $entity)
    {
        $report->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn('urn:activity:123');

        $report->getReasonCode()
            ->shouldBeCalled()
            ->willReturn(2);

        $report->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->getReport()
            ->willReturn($report);

        $verdict->isUpheld()
            ->willReturn(true);

        $this->entitiesBuilder->single(123)
            ->willReturn($entity);

        $this->dispatcher->trigger('notification', 'all', Argument::that(function($opts) {
            return $opts['params']['action'] === 'marked as nsfw. You can appeal this decision';
        }))
            ->shouldBeCalled();

        $this->onAction($verdict);
    }

    function it_should_send_a_removed_notification(Verdict $verdict, Report $report, Entity $entity)
    {
        $report->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn('urn:activity:123');

        $report->getReasonCode()
            ->shouldBeCalled()
            ->willReturn(4);

        $report->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->getReport()
            ->willReturn($report);

        $verdict->isUpheld()
            ->willReturn(true);

        $this->entitiesBuilder->single(123)
            ->willReturn($entity);

        $this->dispatcher->trigger('notification', 'all', Argument::that(function($opts) {
            return $opts['params']['action'] === 'removed. You can appeal this decision';
        }))
            ->shouldBeCalled();

        $this->onAction($verdict);
    }

    function it_should_send_a_restored_notification(Verdict $verdict, Report $report, Entity $entity)
    {
        $report->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn('urn:activity:123');

        $report->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getReport()
            ->willReturn($report);

        $verdict->isUpheld()
            ->willReturn(false);

        $this->entitiesBuilder->single(123)
            ->willReturn($entity);

        $this->dispatcher->trigger('notification', 'all', Argument::that(function($opts) {
            return $opts['params']['action'] === 'restored by the community appeal jury';
        }))
            ->shouldBeCalled();

        $this->onAction($verdict);
    }

    function it_should_not_send_a_notification(Verdict $verdict, Report $report, Entity $entity)
    {
        $report->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn('urn:activity:123');

        $report->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $verdict->getReport()
            ->willReturn($report);

        $verdict->isUpheld()
            ->willReturn(false);

        $this->entitiesBuilder->single(123)
            ->willReturn($entity);

        $this->dispatcher->trigger('notification', 'all')
            ->shouldNotBeCalled();

        $this->onAction($verdict);
    }

}
