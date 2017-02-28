<?php
/**
 * Minds Monetization Ledger
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1\monetization;

use Minds\Components\Controller;
use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class ledger extends Controller implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }

        $currentUser = Core\Sandbox::user(Core\Session::getLoggedInUser());

        if (!isset($pages[1]) || !$pages[1]) {
            $user = $currentUser;
        } else {
            $user = new Entities\User($pages[1]);

            if (!$user || !$user->guid) {
                return Factory::response([ 'status' => 'error' ]);
            }
        }

        if ($user->guid != $currentUser->guid && !Core\Session::isAdmin()) {
            return Factory::response([ 'status' => 'error' ]);
        }

        $users = Core\Di\Di::_()->get('Monetization\Users');
        $users->setUser($user);

        switch ($pages[0]) {
            case 'list':

                $offset = isset($_GET['offset']) ? $_GET['offset'] : '';
                $list = $users->getTransactions(12, $offset);

                return Factory::response([
                    'ledger' => $list ?: [],
                    'load-next' => $list ? end($list)['guid'] : false,
                ]);
                break;
        }

        return Factory::response([]);
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
