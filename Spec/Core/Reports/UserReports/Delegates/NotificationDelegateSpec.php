<?php

namespace Spec\Minds\Core\Reports\UserReports\Delegates;

use Minds\Core\Reports\UserReports\Delegates\NotificationDelegate;
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotificationDelegateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NotificationDelegate::class);
    }

    function it_should_send_a_notification(EventsDispatcher $eventsDispatcher)
    {
        $this->beConstructedWith($eventsDispatcher);
        $eventsDispatcher->trigger('notification', 'all', Argument::that(function($arr) {
            return $arr['to'] === [ 123 ]
                && $arr['message'] === 'Thanks for your report, and for making Minds a safer place';
        }))
            ->shouldBeCalled();

        $userReport = new UserReport;
        $userReport->setReporterGuid(123);
        $this->onAction($userReport);
    }
}
