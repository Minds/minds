<?php

namespace Minds\Controllers\api\v2\newsfeed;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Entities\Activity;
use Minds\Interfaces;

class pin implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'You must send an Activity GUID']);
        }

        /** @var Activity $activity */
        $activity = Entities\Factory::build($pages[0]);
        $user = Core\Session::getLoggedinUser();
        $user->addPinned($activity->guid);
        $user->save();

        Core\Session::regenerate(false);
        //sync our changes to other sessions
        (new Core\Data\Sessions())->syncAll($user->guid);
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'You must send an Activity GUID']);
        }
        /** @var Activity $activity */
        $activity = Entities\Factory::build($pages[0]);
        $user = Core\Session::getLoggedinUser();
        $user->removePinned($activity->guid);
        $user->save();

        Core\Session::regenerate(false);
        //sync our changes to other sessions
        (new Core\Data\Sessions())->syncAll($user->guid);
        return Factory::response([]);
    }

}