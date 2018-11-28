<?php
/**
 * Minds Experimetns
 *
 * @version 2
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v2;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Common\Cookie;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities;

class experiments implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $response = [];

        if (!isset($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Id of experiment must be provided in the URI',
            ]);
        }

        $id = $pages[0];

        if (!isset($_COOKIE['mexp'])) {
            //@TODO make this more unique
            $cookieid = uniqid(true);

            $cookie = new Cookie();
            $cookie
                ->setName('mexp')
                ->setValue($cookieid)
                ->setExpire(time() + (60 * 60 * 24 * 30 * 12))
                ->setPath('/')
                ->create();

            $_COOKIE['mexp'] = $cookieid;
        }

        $manager = Di::_()->get('Experiments\Manager');

        if (Core\Session::isLoggedIn()) {
            $manager->setUser(Core\Session::getLoggedInUser());
        }

        $bucket = $manager->getBucketForExperiment($id);

        $response['experimentId'] = $id;
        $response['bucketId'] = $bucket->getId();

        return Factory::response($response);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
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
