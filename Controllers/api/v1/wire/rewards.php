<?php
/**
 * Minds Wire Rewards
 *
 * @version 1
 * @author Emiliano Balbuena
 *
 */
namespace Minds\Controllers\api\v1\wire;

use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Payments;
use Minds\Entities;
use Minds\Entities\User;

class rewards implements Interfaces\Api
{
    /**
     * GET
     */
    public function get($pages)
    {
        $user = $pages[0] ? new User($pages[0]) : Core\Session::getLoggedinUser();

        if (!$user || !$user->guid) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Unknown user'
            ]);
        }

        $response = [];

        $response['wire_rewards'] = $user->getWireRewards() ?: null;

        if (is_string($response['wire_rewards'])) {
            $response['wire_rewards'] = json_decode($response['wire_rewards'], true);
        }

        $response['merchant'] = $user->getMerchant() ?: false;

        return Factory::response($response);
    }

    /**
     * POST
     */
    public function post($pages)
    {
        $owner = Core\Session::getLoggedinUser();
        $rewards = $_POST['rewards'] ?: [];

        if ($rewards) {
            if (
                !isset($rewards['description']) ||
                !isset($rewards['rewards']['points']) ||
                !is_array($rewards['rewards']['points']) ||
                !isset($rewards['rewards']['money']) ||
                !is_array($rewards['rewards']['money'])
            ) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'Missing required fields'
                ]);
            }
        }

        $update = [];
        $response = [];

        $owner->setWireRewards($rewards ?: []);
        $update['wire_rewards'] = json_encode($rewards);

        $db = new Core\Data\Call('entities');
        $db->insert($owner->guid, $update);
        //update session also
        Core\Session::regenerate(false, $owner);
        //sync our change to our other sessions
        (new Core\Data\Sessions())->syncAll($owner->guid);

        $response['channel'] = $owner->export();

        return Factory::response($response);
    }

    /**
     * PUT
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * DELETE
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
