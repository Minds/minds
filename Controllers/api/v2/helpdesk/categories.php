<?php

namespace Minds\Controllers\api\v2\helpdesk;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Category\Manager;
use Minds\Interfaces\Api;

class categories implements Api
{
    public function get($pages)
    {
        $limit = 30;

        if (isset($_GET['limit'])) {
            $limit = intval($_GET['limit']);
        }

        $offset = 0;

        if (isset($_GET['offset'])) {
            $offset = intval($_GET['offset']);
        }

        $recursive = false;

        if (isset($_GET['recursive'])) {
            $recursive = boolval($_GET['recursive']);
        }

        /** @var Manager $manager */
        $manager = Di::_()->get('Helpdesk\Category\Manager');

        $categories = $manager->getAll([
            'limit' => $limit,
            'offset' => $offset,
            'recursive' => $recursive,
        ]);

        return Factory::response([
            'status' => 'success',
            'categories' => Factory::exportable($categories)
        ]);
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