<?php
/**
 * Minds Messenger Search
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Plugin\Messenger\Controllers\api\v2;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;
use Minds\Plugin\Messenger;
use Minds\Interfaces;
use Minds\Api\Factory;

class search implements Interfaces\Api
{

    /**
     * Returns users and conversation guids
     * @param array $pages
     *
     * API:: /v1/conversations/search
     */
    public function get($pages)
    {
        Factory::isLoggedIn();

        $response = [];

        if (!isset($_GET['q']) || !$_GET['q']) {
            return Factory::response([
              'status' => 'error',
              'message' => 'Missing query'
            ]);
        }

        $params = [
          'limit' => isset($_GET['limit']) ? $_GET['limit'] : 24,
          'type' => 'user'
        ];

        $_GET['q'] = "({$_GET['q']})^5 OR (*{$_GET['q']}*)";

        $guids = (new Core\Search\Documents())->query($_GET['q'], $params);
        $response = [];

        if ($guids) {
            $users = Core\Entities::get([
              'guids' => $guids
            ]);
            $conversations = [];
            foreach($users as $user){
                $conversations[] = (new Messenger\Entities\Conversation())
                                      ->setParticipant($user->guid)
                                      ->setParticipant(Core\Session::getLoggedInUserGuid());
            }

            $response['conversations'] = Factory::exportable($conversations);
            $response['load-next'] = (int) $_GET['offset'] + $_GET['limit'] + 1;
        }

        return Factory::response($response);

    }

    public function post($pages){
        return Factory::response([]);
    }

    public function put($pages){
        return Factory::response([]);
    }

    public function delete($pages){
        return Factory::response([]);
    }

}
