<?php

namespace Minds\Controllers\api\v2\helpdesk\categories;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Category\Manager;
use Minds\Interfaces\Api;

class category implements Api
{
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'uuid must be provided']);
        }
        $uuid = $pages[0];

        /** @var Manager $manager */
        $manager = Di::_()->get('Helpdesk\Category\Manager');

        $result = $manager->getAll([
            'uuid' => $uuid
        ]);

        $category = null;

        // get a single category
        if (count($result) > 0) {
            $category = $result[0];
        }

        if ($category) {
            /** @var \Minds\Core\Helpdesk\Question\Manager $questionsManager */
            $questionsManager = Di::_()->get('Helpdesk\Question\Manager');

            $questions = $questionsManager->getAll([
                'category_uuid' => $category->getUuid()
            ]);
            $category->setQuestions($questions);

            if ($category->getParentUuid()) {
                $branch = $manager->getBranch($category->getParentUuid());
                $category->setParent($branch);
            }
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