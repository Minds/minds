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
            'p2p_media_disabled' => $user->isP2PMediaDisabled()
        ]);
    }

    public function post($pages)
    {
        $user = Core\Session::getLoggedInUser();

        $user->toggleP2PMediaDisabled(true);
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

        $user->toggleP2PMediaDisabled(false);
        $user->save();

        return Factory::response(['done' => true]);
    }

}