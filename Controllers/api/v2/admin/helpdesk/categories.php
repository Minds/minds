<?php


namespace Minds\Controllers\api\v2\admin\helpdesk;

use Minds\Interfaces\Api;
use Minds\Interfaces\ApiAdminPam;
use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Repository;

class categories implements Api, ApiAdminPam
{
    public function get($pages)
    {
        return Factory::response([]);
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