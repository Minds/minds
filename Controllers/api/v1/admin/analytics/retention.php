<?php
/**
 * Minds Admin: Analytics : Retention
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\admin\analytics;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use DateTime;

class retention implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * Return analytics data
     * @param array $pages
     */
    public function get($pages)
    {
        $response = array();

        $db = new Core\Data\Call('entities_by_time');

        $app = Core\Analytics\App::_()
        ->setMetric('retention');

        $response['retention'] = $app->get(1);


        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        return Factory::response(array());
    }
}
