<?php
/**
 * Minds Admin: Ban
 *
 * @version 1
 * @author Emiliano Balbuena
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Events\Dispatcher;

class ban implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     *
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Ban a user
     * @param array $pages
     */
    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return [
                'error' => true
            ];
        }

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return [
                'error' => true
            ];
        }

        $reason = isset($_POST['subject']) && $_POST['subject'] ? $_POST['subject'] : null;
        $reason_note = isset($_POST['note']) && $_POST['note'] ? $_POST['note'] : null;

        $user->ban_reason = $reason->value == 11 ? $reason_note : $reason->label;

        $user->banned = 'yes';
        $user->code = '';
        $user->save();

        \cache_entity($user);

        (new Core\Data\Sessions())->destroyAll($user->guid);

        Dispatcher::trigger('ban', 'user', $user);

        return Factory::response([
            'done' => true
        ]);
    }

    /**
     * Unban a user
     * @param array $pages
     */
    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return [
                'error' => true
            ];
        }

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return [
                'error' => true
            ];
        }

        $user->banned = 'no';
        $user->save();

        \cache_entity($user);

        return Factory::response([
            'done' => true
        ]);
    }
}
