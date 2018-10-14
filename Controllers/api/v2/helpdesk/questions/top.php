<?php

namespace Minds\Controllers\api\v2\helpdesk;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Question\Repository;
use Minds\Interfaces\Api;

class top implements Api
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

        $category = null;

        if (isset($_GET['category']) && trim($_GET['category']) !== '') {
            $category = trim($_GET['category']);
        }

        /** @var Repository $repo */
        $repo = Di::_()->get('Helpdesk\Question\Repository');

        $questions = $repo->getTop([
            'limit' => $limit,
            'offset' => $offset,
            'category' => $category
        ]);

        return Factory::response([
            'status' => 'success',
            'questions' => $questions
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