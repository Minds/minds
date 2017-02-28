<?php
/**
 * Minds Payouts Ban API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\admin\monetization;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class ban implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * @param array $pages
     */
    public function get($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
              'status' => 'error',
              'message' => "Invalid user"
            ]);
        }

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return Factory::response([
              'status' => 'error',
              'message' => "User not found"
            ]);
        }

        $merchants = Core\Di\Di::_()->get('Monetization\Merchants');
        $merchants->setUser($user);

        return Factory::response([
            'banned' => (bool) $merchants->isBanned()
        ]);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
              'status' => 'error',
              'message' => "Invalid user"
            ]);
        }

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return Factory::response([
              'status' => 'error',
              'message' => "User not found"
            ]);
        }

        $merchants = Core\Di\Di::_()->get('Monetization\Merchants');
        $merchants->setUser($user);

        $done = $merchants->ban();

        if ($done) {
            \cache_entity($user);
            (new Core\Data\Sessions())->destroyAll($user->guid);
        }

        return Factory::response([
            'done' => $done
        ]);
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
              'status' => 'error',
              'message' => "Invalid user"
            ]);
        }

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return Factory::response([
              'status' => 'error',
              'message' => "User not found"
            ]);
        }

        $merchants = Core\Di\Di::_()->get('Monetization\Merchants');
        $merchants->setUser($user);

        $done = $merchants->unban();

        if ($done) {
            \cache_entity($user);
            (new Core\Data\Sessions())->destroyAll($user->guid);
        }

        return Factory::response([
            'done' => $done
        ]);
    }
}
