<?php
/**
 * Minds Admin: User Reports
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class paywall implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * @param array $pages
     */
    public function get($pages)
    {
        $response = [];

        switch ($pages[0]) {
            case "review":

                $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
                $offset = isset($_GET['offset']) ? $_GET['offset'] : "";
                $state = isset($pages[0]) ? $pages[0] : null;

                $guids = (new Core\Payments\Plans\PaywallReview())
                  ->getAll([
                    'limit' => $limit,
                    'offset' => $offset
                  ]);

                $entities = Core\Entities::get(['guids'=>$guids]);
                $response['entities'] = Factory::exportable($entities);
                $response['load-next'] = end($entities)->guid;
            break;
        }

        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {

        $entity = Entities\Factory::build($pages[0]);

        switch ($pages[1]) {
            case "demonetize":
              $entity->paywall = false;
              $entity->save();
              break;
        }

        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
