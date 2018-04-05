<?php


namespace Minds\Controllers\api\v1\admin;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;

class founder implements Interfaces\Api, Interfaces\ApiAdminPam
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error'
            ]);
        }

        $user = new Entities\User($pages[0]);

        $user->founder = true;
        $user->save();

        \cache_entity($user);

        (new Core\Data\Sessions())->syncRemote($user->guid, $user);

        return Factory::response([
            'done' => true
        ]);
    }

    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error'
            ]);
        }

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return Factory::response([
                'status' => 'error'
            ]);
        }

        $user->founder = false;
        $user->save();

        \cache_entity($user);
        (new Core\Data\Sessions())->syncRemote($user->guid, $user);

        return Factory::response([
            'done' => true
        ]);
    }

}