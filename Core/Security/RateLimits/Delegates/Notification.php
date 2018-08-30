<?php
/**
 * Notification delegate
 */
namespace Minds\Core\Security\RateLimits\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\EventsDispatcher;

class Notification
{

    /** @var EventsDispatcher */
    protected $dispatcher;

    public function __construct($dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
    }
    

    public function notify($user, $key)
    {
        $message = "Your channel has been rate limited due to high activity. Please try again later";
        switch ($key) {
            case "ratelimited_interaction:subscribe":
                $message = "Your channel has been rate limited due to a high number of subscribes. 
                            Please try again in 5 minutes";
                break;
        }

        $response = $this->dispatcher->trigger('notification', 'all', [
            'to' => [ $user->guid ],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => [ 'message' => $message ],
            'message' => $message,
        ]);

    }

}
