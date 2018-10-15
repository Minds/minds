<?php
/**
 * Minds Admin: Verify
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class verify implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     *
     */
    public function get($pages)
    {
        /**
         * This needs its own class.. done for speed atm
         */
        $db = new Core\Data\Call('entities_by_time');

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 24;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : '';

        $requests = $db->getRow('verify:requests', [ 'limit' => $limit, 'offset' => $offset ]);

        $response = [];
        foreach ($requests as $request) {
            $payload = json_decode($request, true);
            $user = Entities\Factory::build($payload['guid']);
            $payload['user'] = $user->export();
            $response['requests'][] = $payload;
        }

        if ($response['requests']) {
            $response['load-next'] = end(array_keys($requests));
        }

        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Verify a user
     * @param array $pages
     */
    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return [
                'error' => true
            ];
        }

        $db = new Core\Data\Call('entities_by_time');

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return [
                'error' => true
            ];
        }

        $user->verified = true;
        $user->save();

        \cache_entity($user);

        $db->removeAttributes('verify:requests', [ $user->guid ]);

        return Factory::response([
            'done' => true
        ]);
    }

    /**
     * Unverify a user
     * @param array $pages
     */
    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return [
                'error' => true
            ];
        }

        $db = new Core\Data\Call('entities_by_time');

        $user = new Entities\User($pages[0]);

        if (!$user || !$user->guid) {
            return [
                'error' => true
            ];
        }

        $user->verified = false;
        $user->save();

        \cache_entity($user);

        $db->removeAttributes('verify:requests', [ $user->guid ]);

        return Factory::response([
            'done' => true
        ]);
    }
}
