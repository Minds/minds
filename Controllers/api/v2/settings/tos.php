<?php
/**
 * Update last seen TOS
 */

namespace Minds\Controllers\api\v2\settings;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Interfaces;

class tos implements Interfaces\Api
{

    public function get($pages)
    {
        return Factory::response(['status' => 'error', 'message' => 'GET is not supported for this endpoint']);
    }

    public function post($pages)
    {
        $user = Core\Session::getLoggedinUser();

        $user->setLastAcceptedTOS(Core\Config::_()->get('last_tos_update'))
            ->save();


        return Factory::response(['status' => 'success', 'timestamp' => $user->getLastAcceptedTOS()]);
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
