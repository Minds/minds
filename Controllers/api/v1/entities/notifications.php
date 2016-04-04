<?php
/**
 * Minds Entity Notifications API
 * Also, used on Archive plugin view
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\entities;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Notification;

class notifications implements Interfaces\Api
{
    public function get($pages)
    {
        if (count($pages) < 1) {
            return Factory::response([]);
        }

        $entity = Entities\Factory::build($pages[0]);
        $user = Core\Session::getLoggedInUser();

        if (!$entity) {
            return Factory::response([
                'error' => true,
                'message' => 'Bad parameters'
            ]);
        }

        $entity_notifications = new Notification\Entity($entity);

        return Factory::response([
            'is:muted' => $entity_notifications->isMuted($user)
        ]);
    }

    public function post($pages)
    {
        if (count($pages) < 2) {
            return Factory::response([]);
        }

        $entity = Entities\Factory::build($pages[0]);
        $action = $pages[1];
        $user = Core\Session::getLoggedInUser();

        $response = [];

        if (!$entity || !$action) {
            return Factory::response([
                'error' => true,
                'message' => 'Bad parameters'
            ]);
        }

        $entity_notifications = new Notification\Entity($entity);

        switch ($action) {
            case 'mute':
                $response['done'] = $entity_notifications->mute($user);
                break;

            case 'unmute':
                $response['done'] = $entity_notifications->unmute($user);
                break;
        }

        return Factory::response($response);
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
