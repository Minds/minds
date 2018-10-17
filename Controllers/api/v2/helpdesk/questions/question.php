<?php

namespace Minds\Controllers\api\v2\helpdesk\questions;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Question\Repository;
use Minds\Interfaces\Api;

class question implements Api
{
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'uuid must be provided']);
        }

        $uuid = $pages[0];
        // get a single question
        /** @var Repository $repo */
        $repo = Di::_()->get('Helpdesk\Question\Repository');

        $result = $repo->getAll(['question_uuid' => $uuid]);

        $question = null;

        if (count($result) > 0) {
            $question = $result[0];
        }

        return Factory::response([
            'status' => 'success',
            'question' => $question->export()
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