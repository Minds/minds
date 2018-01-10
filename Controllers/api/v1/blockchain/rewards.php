<?php

/**
 * Crypto Rewards
 *
 * @author Mark
 */

namespace Minds\Controllers\api\v1\blockchain;

use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;

class rewards implements Interfaces\Api
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

        $response = [];

        switch ($pages[0]) {
            case "balance":
                $balance = Di::_()->get('Rewards\Balance');
                $balance->setUser(Session::getLoggedinUser());

                $response = [
                    'balance' => $balance->get()
                ];
                break;
            case "ledger":
                $repo = Di::_()->get('Rewards\Repository');
                $result = $repo->getList([
                    'from' => $from,
                    'to' => $to,
                    'user_guid' => Session::getLoggedInUser()->guid,
                    'offset' => $offset
                ]);
                
                $response = [
                    'rewards' => Factory::exportable($result['rewards']),
                    'load-next' => base64_encode($result['token'])
                ];
                break;
        }
        
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
