<?php
/**
 * Minds Admin: User Reports
 *
 * @version 1
 * @author Emi Balbuena
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class reports implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * @param array $pages
     */
    public function get($pages)
    {
        $response = [];

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "";
        $state = isset($pages[0]) ? $pages[0] : null;

        $reports = (new Core\Reports())->getQueue($limit, $offset, $state);

        if ($reports) {
            $response['reports'] = $reports;
            $response['load-next'] = end($reports)['_id'];
        }

        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
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
