<?php
/**
 * API for enabling/disabling canary
 */
namespace Minds\Controllers\api\v2;

use Minds\Api\Factory;
use Minds\Common\Cookie;
use Minds\Core\Config;
use Minds\Core\Session;
use Minds\Interfaces;

class canary implements Interfaces\Api
{

    public function get($pages)
    {
        $user = Session::getLoggedInUser();
        if (!$user) {
            Factory::response([
                'status' => 'error',
                'message' => 'You are not logged in'
            ]);
        }
        return Factory::response([
            'enabled' => (bool) $user->isCanary(),
        ]);
    }

    public function post($pages)
    {
        return $this->delete($pages);
    }

    public function put($pages)
    {
        $user = Session::getLoggedInUser();
        $user->setCanary(true);
        $user->save();
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $user = Session::getLoggedInUser();
        $user->setCanary(false);
        $user->save();
        return Factory::response([]);
    }
    
}


