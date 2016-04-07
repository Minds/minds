<?php
/**
 * Minds Group API
 * Groups listing endpoints
 */
namespace Minds\Plugin\Groups\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Entities;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;

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

        $indexDb = Di::_()->get('Database\Cassandra\Indexes');
        $relDb = Di::_()->get('Database\Cassandra\Relationships');

        if (!isset($pages[0])) {
            $pages[0] = "featured";
        }

        $opts = [
          'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 12,
          'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
        ];

        switch ($pages[0]) {
          case "featured":
            $guids = $indexDb->get('group:featured', $opts);
            end($guids); //get last in array
            $response['load-next'] =  (string) key($guids);
            break;
          case "member":
            $relDb->setGuid($user->guid);
            $guids = $relDb->get('member', $opts);
            break;
          case "all":
          default:
            $guids = $indexDb->get('group', $opts);
            break;
        }

        if(!$guids){
          return;
        }

        $groups = Entities::get(['guids' => $guids]);

        $response['groups'] = Factory::exportable($groups);

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
