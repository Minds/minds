<?php
/**
 * Helpdesk single questions endpoint
 */
namespace Minds\Controllers\api\v2\helpdesk\questions;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Question\Manager;
use Minds\Core\Helpdesk\Question\Repository;
use Minds\Core\Session;
use Minds\Interfaces\Api;

class question implements Api
{
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'uuid must be provided']);
        }

        $uuid = $pages[0];

        /** @var Manager $manager */
        $manager = Di::_()->get('Helpdesk\Question\Manager');

        $opts = ['question_uuid' => $uuid];

        $question = $manager->get($uuid, Session::getLoggedInUser()->guid);

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
