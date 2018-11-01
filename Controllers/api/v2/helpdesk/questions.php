<?php

namespace Minds\Controllers\api\v2\helpdesk;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Question\Repository;
use Minds\Core\Session;
use Minds\Core\Votes\Manager;
use Minds\Core\Votes\Vote;
use Minds\Interfaces\Api;

class questions implements Api
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

        $category_uuid = null;

        if (isset($_GET['category_uuid']) && trim($_GET['category_uuid']) !== '') {
            $category_uuid = trim($_GET['category_uuid']);
        }

        /** @var Repository $repo */
        $repo = Di::_()->get('Helpdesk\Question\Repository');

        $questions = $repo->getAll([
            'limit' => $limit,
            'offset' => $offset,
            'category_uuid' => $category_uuid
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

    // upvotes and downvotes
    public function put($pages)
    {
        $question_uuid = null;
        $vote_direction = null;

        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => ':question_uuid must be provided']);
        }

        $question_uuid = $pages[0];

        if (!isset($pages[1])) {
            return Factory::response(['status' => 'error', 'message' => 'vote direction must be provided']);
        }

        if (!(in_array($pages[1], ['up', 'down', 'delete']))) {
            return Factory::response([
                'status' => 'error',
                'message' => "vote direction can only be either 'up' or 'down'"
            ]);
        }

        $vote_direction = $pages[1];

        /** @var Repository $repo */
        $repo = Di::_()->get('Helpdesk\Question\Repository');

        if ($vote_direction === 'delete') {
            $result = $repo->unvote($question_uuid);
        } else {
            $result = $repo->vote($question_uuid, $vote_direction);
        }

        if ($result === false) {
            return Factory::response([
                'status' => 'error',
                'message' => "Error saving your vote"
            ]);
        }

        return Factory::response([
            'status' => 'success',
            'done' => $done,
        ]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}