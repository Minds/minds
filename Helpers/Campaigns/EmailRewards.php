<?php
namespace Minds\Helpers\Campaigns;

use Minds\Core;
use Minds\Core\Config;
use Minds\Helpers;
use Minds\Entities;

/**
 * Helper for Email Rewards
 * @todo Avoid static and use proper DI
 */
class EmailRewards
{
    /**
     * Grants an email reward to an user
     * @param  string $campaign
     * @param  mixed  $user_guid
     * @return null
     */
    public static function reward($campaign, $user_guid)
    {
        if (!is_numeric($user_guid)) {
            return;
        }

        $user = new Entities\User($user_guid);

        $cacher = Core\Data\cache\factory::build('apcu');
        $label = $campaign;
        switch ($campaign) {
          case "retention-1":
          case "retention-3":
          case "retention-7":
          case "retention-28":
            $points = 100;
            $label = "Check-in bonus";
            break;
          case "january-12-2017":
            $validator = $_GET['validator'];
            if ($validator == sha1($campaign . $user->guid . Config::_()->get('emails_secret'))) {
                $points = 1000;
            } else {
                echo "Validator failed"; exit;
            }
            break;
          default:
            return;
        }

        if ($cacher->get("rewarded:email:$campaign:$user_guid") == true) {
            return;
        }

        $db = new Core\Data\Call('entities_by_time');
        $ts = Helpers\Analytics::buildTS("day", time());
        $row = $db->getRow("analytics:rewarded:email:$campaign", ['offset'=> $user_guid, 'limit'=>1]);
        if (!$row || key($row) != $user_guid) {
            $db->insert("analytics:rewarded:email:$campaign", [ $user_guid => time()]);

            Helpers\Wallet::createTransaction($user_guid, $points, $user_guid, "Email Click ($label)");
        }
        $cacher->set("rewarded:email:$campaign:$user_guid", true, strtotime('tomorrow', time()) - time());
    }
}
