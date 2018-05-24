<?php

namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Config;

class pledges implements Interfaces\Api, Interfaces\ApiAdminPam
{
    public function get($pages)
    {
        $offset = $_GET['offset'] ? base64_decode($_GET['offset']) : '';
        /** @var Core\Blockchain\Pledges\Repository $repo */
        $repo = Di::_()->get('Blockchain\Pledges\Repository');

        $result = $repo->getList(['offset' => $offset]);

        $response['pledges'] = Factory::exportable($result['pledges']);
        $response['load-next'] = base64_encode($result['token']);

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