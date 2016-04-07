<?php
/**
 * Minds Group API
 * Notification-related endpoints
 */
namespace Minds\Plugin\Groups\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Security\ACL;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Entities\User;

use Minds\Plugin\Groups;
use Minds\Plugin\Groups\Exceptions\GroupOperationException;

class management implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $actor = Session::getLoggedInUser();

        if (!$group || !$group->getGuid())
        {
            return Factory::response([
                'done' => false,
                'error' => 'No group'
            ]);
        }

        if (!isset($pages[1]) || !$pages[1] || !ctype_alnum($pages[1])) {
            return Factory::response([
                'done' => false,
                'error' => 'Invalid user'
            ]);
        }

        $member = new User($pages[1]);

        if (!$member || !$member->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        $management = (new Groups\Core\Management($group))
          ->setActor($actor);

        try {
            $granted = $management->grant($member);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'done' => $granted
        ]);
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $actor = Session::getLoggedInUser();

        if (!$group || !$group->getGuid())
        {
            return Factory::response([
                'done' => false,
                'error' => 'No group'
            ]);
        }

        if (!isset($pages[1]) || !$pages[1] || !ctype_alnum($pages[1])) {
            return Factory::response([
                'done' => false,
                'error' => 'Invalid user'
            ]);
        }

        $member = new User($pages[1]);

        if (!$member || !$member->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        $management = (new Groups\Core\Management($group))
          ->setActor($actor);

        try {
            $revoked = $management->revoke($member);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'done' => $revoked
        ]);
    }
}
