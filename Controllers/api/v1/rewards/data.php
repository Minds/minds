<?php

/**
 * Minds Rewards API
 *  returns founder's data and rewards
 * @version 1
 * @author Marcelo Rivera
 */

namespace Minds\Controllers\api\v1\rewards;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\FounderRewards\RewardType;
use Minds\Interfaces;

class data implements Interfaces\Api
{
    public function get($pages)
    {
        if (!isset($_GET['uuid']) || !$_GET['uuid']) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Missing query'
            ]);
        }

        list($uuid, $validator) = explode('-', $_GET['uuid']);

        $founderRewards = new Core\FounderRewards\FounderRewards();
        $founders = $founderRewards->getFounders();
        $founder = $this->validateUUID($founders, $uuid);
        if (!$founder || $founder->claimed) {
            return Factory::response(['valid' => false]);
        }

        if ($validator != sha1($founder->name . $founder->email . $founder->uuid . $founder->amount)) {
            return Factory::response([
                'valid' => false,
                'validator' => $validator,
                //'sha1' => sha1($founder->name . $founder->email . $founder->uuid . $founder->amount)
                ]);
        }

        $rewards = $founderRewards->getEligibleRewards($founder->amount);
        $rewardNames = [];
        foreach ($rewards as $reward) {
             $rewardNames[] = $reward->name;
        }
        $requiresTShirtSize = RewardType::requiresTShirtSize($rewards);
        $requiresCellPhone = RewardType::requiresCellPhone($rewards);

        return Factory::response([
            'name' => $founder->name,
            'amount' => $founder->amount,
            'rewards' => $rewardNames,
            'requiresTShirtSize' => $requiresTShirtSize,
            'requiresCellPhone' => $requiresCellPhone
        ]);
        return Factory::response(['valid' => false]);
    }

    private function validateUUID($founders, $uuid)
    {
        foreach ($founders as $founder) {
            if ($founder->uuid == $uuid) {
                return $founder;
            }
        }
        return null;
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public
    function delete($pages)
    {
        return Factory::response([]);
    }
}
