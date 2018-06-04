<?php
/**
 * Minds Entity Notifications API
 * Also, used on Archive plugin view
 *
 * @version 1
 * @author Emi Balbuena
 */

namespace Minds\Controllers\api\v1\entities;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Notification;
use Minds\Entities;
use Minds\Interfaces;

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

        $manager = new Notification\PostSubscriptions\Manager();
        $manager
            ->setUserGuid($user->guid)
            ->setEntityGuid($entity->guid);

        $subscription = $manager->get();

        $isMuted = !$subscription->isFollowing();

        if ($subscription->isEphemeral()) {
            $entity_notifications = new Notification\Entity($entity);
            $isMuted = $entity_notifications->isMuted($user);
        }

        return Factory::response([
            'is:muted' => $isMuted
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

        $manager = new Notification\PostSubscriptions\Manager();
        $manager
            ->setEntityGuid($entity->guid)
            ->setUserGuid($user->guid);

        switch ($action) {
            case 'mute':
                $response['done'] = $manager->unfollow();

                if ($entity->entity_guid) {
                    $manager
                        ->setEntityGuid($entity->entity_guid)
                        ->setUserGuid($user->guid)
                        ->unfollow();
                }
                break;

            case 'unmute':
                $response['done'] = $manager->follow();

                if ($entity->entity_guid) {
                    $manager
                        ->setEntityGuid($entity->guid)
                        ->setUserGuid($user->guid)
                        ->follow();
                }
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
