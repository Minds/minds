<?php

/**
 * Minds Rewards API
 *  claims rewards for a given founder
 * @version 1
 * @author Marcelo Rivera
 */

namespace Minds\Controllers\api\v1\rewards;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Data;
use Minds\Core\FounderRewards\RewardType;
use Minds\Helpers;
use Minds\Interfaces;

class claim implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (!isset($_POST['uuid']) || !$_POST['uuid']) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Missing uuid'
            ]);
        }

        list($uuid, $validator) = explode('-', $_POST['uuid']);

        $founderRewards = new Core\FounderRewards\FounderRewards();

        $founders = $founderRewards->getFounders();
        $founder = null;

        foreach ($founders as $f) {
            if ($f->uuid == $uuid) {
                $founder = $f;
                break;
            }
        }

        if (!$founder) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Person not found'
            ]);
        }

        if ($validator != sha1($founder->name . $founder->email . $founder->uuid . $founder->amount)) {
            return Factory::response(['valid' => false]);
        }

        $rewards = $founderRewards->getEligibleRewards($founder->amount);

        if (RewardType::requiresTShirtSize($rewards) && !isset($_POST['tshirtSize'])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Missing T-shirt size'
            ]);
        }

        $founder->tshirtSize = isset($_POST['tshirtSize']) ? $_POST['tshirtSize'] : '';
        $founder->address = isset($_POST['address']) ? $_POST['address'] : '';

        $user = Core\Session::getLoggedinUser();

        $user->founder = true;
        $user->save();

        $this->checkAndRewardPoints($rewards, $user);
        $this->checkAndRewardBadges($rewards, $user);
        $this->checkAndRewardPage($rewards, $founder, $user);

        $founder->guid = (string) $user->guid;

        //update spreadsheet
        $founderRewards->claimReward($founder);

        return Factory::response([
            'status' => 'success',
            'range' => $founder->rowNumber
        ]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    private function findRewardByName($rewards, $name)
    {
        return array_filter($rewards, function ($reward) use (&$name) {
            return $reward->name == $name;
        });
    }

    private function checkAndRewardPoints($rewards, $user)
    {
        $pointsReward = $this->findRewardByName($rewards, '10,000 points');
        if (!empty($pointsReward)) {
            Helpers\Wallet::createTransaction($user->guid, 10000, null, "Wefunder Reward");
            Helpers\Wallet::logPurchasedPoints($user->guid, 10000);
        }
    }

    private function checkAndRewardBadges($rewards, $user)
    {
        $badgesReward = $this->findRewardByName($rewards, 'Investor Badges');
        if (!empty($badgesReward)) {
            $badges = $user->getBadges();
            if (is_null($badges)) {
                $badges = [];
            }
            if (!array_search('founder', $badges)) {
                $badges[] = 'founder';
                $user->setBadges('badges');
                $user->save();
            }
        }

    }

    private function checkAndRewardPage($rewards, $founder, $user)
    {
        $foundersPageReward = $this->findRewardByName($rewards, 'Official Founders page');
        if (!empty($foundersPageReward)) {
            $db = new Data\Call('entities_by_time');
            $db->insert('wefunder', [$founder->uuid => ['name' => $founder->name], 'channel_guid' => $user->guid]);
        }
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
