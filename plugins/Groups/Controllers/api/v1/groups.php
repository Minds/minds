<?php
/**
 * Minds Group API
 * Groups listing endpoints
 */
namespace Minds\Plugin\Groups\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Entities;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Plugin\Groups\Core\UserGroups;
use Minds\Plugin\Groups\Core\Featured;

class groups implements Interfaces\Api
{
    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/groups/:filter
     */
    public function get($pages)
    {
        $groups = [];
        $user = Session::getLoggedInUser();

        if (!isset($pages[0])) {
            $pages[0] = "featured";
        }

        switch ($pages[0]) {
          case "featured":

            $groups = (new Featured())->getFeatured([
                'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 12,
                'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
            ]);

            if ($groups) {
                $response['load-next'] =  (string) end($groups)->getFeaturedId();
            }

            break;
          case "member":
            if (!$user) {
                return Factory::response([]);
            }

            $groups = (new UserGroups($user))
            ->getGroups([
                'limit' => 12,
                'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
            ]);
            break;
          case "all":
          default:
            $groups = Entities::get(array(
              'type' => 'group'
            ));

            break;
        }

        $response['groups'] = Factory::exportable($groups);

        if ($user) {
            for ($i = 0; $i < count($response['groups']); $i++) {
                $response['groups'][$i]['is:member'] = $groups[$i]->isMember($user);
                $response['groups'][$i]['is:creator'] = $groups[$i]->isCreator($user);
                $response['groups'][$i]['is:awaiting'] = $groups[$i]->isAwaiting($user);
            }
        }

        if (!isset($response['load-next']) && $groups) {
            $response['load-next'] = (string) end($groups)->getGuid();
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
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
