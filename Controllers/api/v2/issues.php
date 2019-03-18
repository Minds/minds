<?php
/**
 * Minds issues reporting
 *
 * @version 2
 * @author Martin Santangelo
 */
namespace Minds\Controllers\api\v2;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Issues\Issue;
use Minds\Core\Session;

class issues implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        $title = $_POST['title'];
        $description = $_POST['description'];

        if ($description === "wow it works!") {
            return Factory::response([
                'status' => 'error',
                'message' => 'Sorry, we need to fix a few things. Please visit gitlab.com/minds to report issues.'
            ]);
        }

        $description .= " reported by: @" . Session::getLoggedInUser()->username;

        $manager = Di::_()->get('Issues\Manager');

        $issue = new Issue;
        $issue->setTitle($title)
            ->setDescription($description)
            ->setLabels('by user');

        // call gitlab api
        $res = $manager->postIssue($issue, $pages[0]);

        // handle errors
        if ($res['message']) {
            return Factory::response([
                'status' => 'error',
                'error' => $this->formatError($res)
            ]);
        }

        return Factory::response($res);
    }

    private function formatError($res)
    {
        $message = '';
        foreach($res['message'] as $k => $v) {
            $message .= $k. ': ' . implode(', ',$v).PHP_EOL;
        }
        return $message;
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
