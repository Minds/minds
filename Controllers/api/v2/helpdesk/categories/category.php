<?php

namespace Minds\Controllers\api\v2\helpdesk\categories;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Category\Repository;
use Minds\Interfaces\Api;

class category implements Api
{
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'uuid must be provided']);
        }
        $uuid = $pages[0];

        /** @var Repository $repo */
        $repo = Di::_()->get('Helpdesk\Category\Repository');

        $result = $repo->getAll([
            'uuid' => $uuid
        ]);

        $category = null;

        // get a single category
        if (count($result) > 0) {
            $category = $result[0];
        }

        if($category) {
            /** @var \Minds\Core\Helpdesk\Question\Repository $questionsRepo */
            $questionsRepo = Di::_()->get('Helpdesk\Question\Repository');

            $questions = $questionsRepo->getAll(['category_uuid' => $category->getUuid()]);
            $category->setQuestions($questions);
        }

        return Factory::response([
            'status' => 'success',
            'category' => $category->export()
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