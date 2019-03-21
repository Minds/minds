<?php
/**
 * Notification delegate for User Reports
 */
namespace Minds\Core\Reports\UserReports\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Reports\UserReports\UserReport;

class NotificationDelegate
{
    /** @var EventsDispatcher */
    protected $dispatcher;

    public function __construct($dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function onAction(UserReport $userReport)
    {
        $message = "Thanks for your report, and for making Minds a safer place";
        $this->dispatcher->trigger('notification', 'all', [
            'to' => [$userReport->getReporterGuid()],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => ['message' => $message],
            'message' => $message,
        ]);
    }

}
