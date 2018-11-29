<?php

namespace Minds\Controllers\api\v2\helpdesk;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Interfaces\Api;
use Minds\Core\Session;

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

        /** @var \Minds\Core\Helpdesk\Question\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');

        $category = Di::_()->get('Helpdesk\Category');
        $category->setUuid($category_uuid);

        $manager->setCategory($category);

        $questions = $manager->getAll([
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
        $direction = null;

        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => ':question_uuid must be provided']);
        }

        $question_uuid = $pages[0];

        if (!isset($pages[1])) {
            return Factory::response(['status' => 'error', 'message' => 'vote direction must be provided']);
        }

        if (!(in_array($pages[1], ['up', 'down']))) {
            return Factory::response([
                'status' => 'error',
                'message' => "vote direction can only be either 'up' or 'down'"
            ]);
        }

        $direction = $pages[1];
        $question = Di::_()->get('Helpdesk\Question');
        $question->setUuid($question_uuid);

        /** @var \Minds\Core\Helpdesk\Question\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Votes\Manager');
        $manager
            ->setDirection($direction)
            ->setUser(Session::getLoggedInUser())
            ->setQuestion($question);

        $result = $manager->vote();

        if ($result === false) {
            return Factory::response([
                'status' => 'error',
                'message' => "Error saving your vote"
            ]);
        }

        return Factory::response([
            'status' => 'success',
        ]);
    }

    public function delete($pages)
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

        if (!(in_array($pages[1], ['up', 'down']))) {
            return Factory::response([
                'status' => 'error',
                'message' => "vote direction can only be either 'up' or 'down'"
            ]);
        }

        $direction = $pages[1];
        $question = Di::_()->get('Helpdesk\Question');
        $question->setUuid($question_uuid);

        /** @var \Minds\Core\Helpdesk\Question\Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Votes\Manager');
        $manager
            ->setDirection($direction)
            ->setUser(Session::getLoggedInUser())
            ->setQuestion($question);

        $result = $manager->delete();

        if ($result === false) {
            return Factory::response([
                'status' => 'error',
                'message' => "Error saving your vote"
            ]);
        }

        return Factory::response([
            'status' => 'success',
        ]);
    }

}
