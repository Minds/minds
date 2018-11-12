<?php

namespace Minds\Controllers\api\v2\helpdesk\questions;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Question\Manager;
use Minds\Core\Helpdesk\Question\Repository;
use Minds\Interfaces\Api;

class top implements Api
{
    public function get($pages)
    {
        $limit = 8;

        if (isset($_GET['limit'])) {
            $limit = intval($_GET['limit']);
        }

        $category = null;

        if (isset($_GET['category']) && trim($_GET['category']) !== '') {
            $category = trim($_GET['category']);
        }

        /** @var Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');

        $questions = $manager->getTop([
            'limit' => $limit,
            'category' => $category
        ]);

        return Factory::response([
            'status' => 'success',
            'questions' => Factory::exportable($questions)
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