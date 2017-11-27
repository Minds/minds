<?php
/**
 * Created by Marcelo
 * Date: 22/06/2017
 * Time: 09:08 AM
 */

namespace Minds\Core\FounderRewards;


class RewardType
{
    public $name;
    public $threshold;
    public $quantity;
    public $requiresTShirtSize;
    public $requiresCellPhone;

    function __construct($name = '', $threshold = '', $quantity = 0, $requiresTShirtSize = false, $requiresCellPhone = false)
    {
        $this->name = $name;
        $this->threshold = $threshold;
        $this->quantity = $quantity;
        $this->requiresTShirtSize = $requiresTShirtSize;
        $this->requiresCellPhone = $requiresCellPhone;
    }

    public static function requiresTShirtSize($rewardTypes)
    {
        foreach ($rewardTypes as $reward) {
            if ($reward->requiresTShirtSize) {
                return true;
            }
        }
        return false;
    }

    public static function requiresCellPhone($rewardTypes)
    {
        foreach ($rewardTypes as $reward) {
            if ($reward->requiresCellPhone) {
                return true;
            }
        }
        return false;
    }
}