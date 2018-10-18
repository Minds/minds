<?php
/**
 * Minds Votes API (formerly known as thumbs)
 *
 * @author emi
 */
namespace Minds\Controllers\api\v1;

use Minds\Core\Di\Di;
use Minds\Core\Security\ACL;
use Minds\Core\Session;
use Minds\Core\Votes\Counters;
use Minds\Core\Votes\Manager;
use Minds\Core\Votes\Vote;
use Minds\Interfaces;
use Minds\Api\Factory;

class votes implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid entity GUID'
            ]);
        }

        $direction = isset($pages[1]) ? $pages[1] : 'up';
        $count = 0;

        try {
            /** @var Counters $counters */
            $counters = Di::_()->get('Votes\Counters');
            $count = $counters->get($pages[0], $direction);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'count' => $count
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return $this->put($pages);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid entity GUID'
            ]);
        }

        $direction = isset($pages[1]) ? $pages[1] : 'up';

        try {
            $vote = new Vote();
            $vote->setEntity($pages[0])
                ->setDirection($direction)
                ->setActor(Session::getLoggedinUser());

            /** @var Manager $manager */
            $manager = Di::_()->get('Votes\Manager');
            $manager->toggle($vote);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid entity GUID'
            ]);
        }

        $direction = isset($pages[1]) ? $pages[1] : 'up';

        try {
            $vote = new Vote();
            $vote->setEntity($pages[0])
                ->setDirection($direction)
                ->setActor(Session::getLoggedinUser());

            /** @var Manager $manager */
            $manager = Di::_()->get('Votes\Manager');
            $manager->cancel($vote);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([]);
    }
}
