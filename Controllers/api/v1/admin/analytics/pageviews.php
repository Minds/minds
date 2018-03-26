<?php


namespace Minds\Controllers\api\v1\admin\analytics;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Interfaces;


class pageviews implements Interfaces\Api, Interfaces\ApiAdminPam
{
    public function get($pages)
    {
        $response = array();

        $app = Core\Analytics\App::_()
            ->setMetric('pageview');

        $pageviews = $app->get(30);

        $response['pageviews'] = $pageviews;

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
