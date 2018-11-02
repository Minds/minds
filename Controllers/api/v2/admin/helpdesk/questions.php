<?php

namespace Minds\Controllers\api\v2\admin\helpdesk;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Entities\Question;
use Minds\Core\Helpdesk\Repository;
use Minds\Interfaces\Api;
use Minds\Interfaces\ApiAdminPam;

class questions implements Api, ApiAdminPam
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        $question = null;
        $answer = null;
        $category_uuid = null;

        if (!isset($_POST['question'])) {
            return Factory::response(['status' => 'error', 'message' => 'question must be provided']);
        }

        $question = $_POST['question'];

        if (!isset($_POST['answer'])) {
            return Factory::response(['status' => 'error', 'message' => 'answer must be provided']);
        }

        $answer = $_POST['answer'];

        if (!isset($_POST['category_uuid'])) {
            return Factory::response(['status' => 'error', 'message' => 'category_uuid must be provided']);
        }

        $category_uuid = $_POST['category_uuid'];

        $entity = new Question();
        $entity->setQuestion($question)
            ->setAnswer($answer)
            ->setCategoryUuid($category_uuid);

        /** @var \Minds\Core\Helpdesk\Question\Repository $repo */
        $repo = Di::_()->get('Helpdesk\Question\Repository');

        $uuid = $repo->add($entity);

        return Factory::response([
            'status' => 'success',
            'uuid' => $uuid,

        ]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $question_uuid = null;

        if (!isset($_POST['question_uuid'])) {
            return Factory::response(['status' => 'error', 'message' => 'question_uuid must be provided']);
        }

        $question_uuid = $_POST['question_uuid'];

        /** @var \Minds\Core\Helpdesk\Question\Repository $repo */
        $repo = Di::_()->get('Helpdesk\Question\Repository');

        $done = $repo->delete($question_uuid);

        return Factory::response([
            'status' => 'success',
            'done' => $done
        ]);
    }

}