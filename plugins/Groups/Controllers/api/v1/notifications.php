<?php
/**
 * Minds Group API
 * Notification-related endpoints
 */
namespace Minds\Plugin\Groups\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\Factory as EntitiesFactory;

use Minds\Plugin\Groups\Core\Notifications as CoreNotifications;

class notifications implements Interfaces\Api
{
    public function get($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        if (!$group->isMember($user)) {
            return Factory::response([
                'muted' => false
            ]);
        }

        $notifications = new CoreNotifications($group);

        return Factory::response([
            'muted' => $notifications->isMuted($user)
        ]);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        if (!$group->isMember($user)) {
            return Factory::response([]);
        }

        $notifications = new CoreNotifications($group);

        switch ($pages[1]) {
            case 'mute':
                $notifications->mute($user);
                return Factory::response([
                    'muted' => true
                ]);
            case 'unmute':
                $notifications->unmute($user);
                return Factory::response([
                    'muted' => false
                ]);
        }

        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
