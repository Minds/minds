<?php

/**
 * Site Contributions
 *
 * @author Mark
 */

namespace Minds\Controllers\api\v2\blockchain;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;

class contributions implements Interfaces\Api
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $cacher = Di::_()->get('Cache');

        $from = isset($_GET['from']) ? $_GET['from'] : strtotime('midnight -7 days') * 1000;
        $to = isset($_GET['to']) ? $_GET['to'] : time() * 1000;
        $offset = $_GET['offset'] ? $_GET['offset'] : '';

        $repo = Di::_()->get('Rewards\Contributions\Repository');
        $result = $repo->getList([
            'from' => $from,
            'to' => $to,
            'user_guid' => Session::getLoggedInUser()->guid,
            'offset' => $offset
        ]);

        $collection = Di::_()->get('Rewards\Contributions\DailyCollection');
        $collection->setContributions($result['contributions']);
        
        $response = [
            'contributions' => $collection->export(),
            'load-next' => base64_encode($result['token'])
        ];

        return Factory::response($response);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        Factory::isLoggedIn();

        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        Factory::isLoggedIn();

        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        Factory::isLoggedIn();

        return Factory::response([]);
    }
}
