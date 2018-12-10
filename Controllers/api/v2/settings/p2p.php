<?php

namespace Minds\Controllers\Api\v2\settings;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Email\EmailSubscription;
use Minds\Entities\User;
use Minds\Interfaces;

class p2p implements Interfaces\Api
{
    public function get($pages)
    {
        $user = Core\Session::getLoggedInUser();

        return Factory::response([
            'p2p_media_enabled' => $user->isP2PMediaEnabled()
        ]);
    }

    public function post($pages)
    {
        $user = Core\Session::getLoggedInUser();

        $user->setP2PMediaEnabled(true);
        $user->save();

        return Factory::response(['done' => true]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $user = Core\Session::getLoggedInUser();

        $user->setP2PMediaEnabled(false);
        $user->save();

        return Factory::response(['done' => true]);
    }

}