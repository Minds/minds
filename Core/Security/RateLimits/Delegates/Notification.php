<?php
/**
 * Notification delegate
 */

namespace Minds\Core\Security\RateLimits\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Events\EventsDispatcher;

class Notification
{

    /** @var EventsDispatcher */
    protected $dispatcher;

    public function __construct($dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function notify($user, $key, $period)
    {
        $message = "Your channel has been rate limited due to high activity. Please try again later";
        switch ($key) {
            case "ratelimited_interaction:subscribe":
                $message = "Your channel has been rate limited due to a high number of subscribes.";
                break;
            case "ratelimited_interaction:voteup":
                $message = "Your channel has been rate limited due to a high number of up votes.";
                break;
            case "ratelimited_interaction:votedown":
                $message = "Your channel has been rate limited due to a high number of down votes.";
                break;
            case "ratelimited_interaction:comment":
                $message = "Your channel has been rate limited due to a high number of comments.";
                break;
            case "ratelimited_interaction:remind":
                $message = "Your channel has been rate limited due to a high number of reminds.";
                break;
        }

        switch ($period) {
            case 300:
                $message .= " Please try again in 5 minutes";
                break;
            case 3600:
                $message .= " Please try again in an hour";
                break;
            case 86400:
                $message .= " Please try again in 24 hours";
                break;
        }

        $response = $this->dispatcher->trigger('notification', 'all', [
            'to' => [$user->guid],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => ['message' => $message],
            'message' => $message,
        ]);

    }

}
