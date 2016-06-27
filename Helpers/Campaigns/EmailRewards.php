<?php
namespace Minds\Helpers\Campaigns;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;

class EmailRewards
{
    public static function reward($campaign, $user_guid)
    {

        if(!is_numeric($user_guid)){
          return;
        }

        $user = new Entities\User($user_guid);

        $cacher = Core\Data\cache\factory::build('apcu');

        switch($campaign){
          case "retention-1":
          case "retention-3":
          case "retention-7":
          case "retention-28":
            $points = 100;
            break;
          case "birthday-2016":
            $points = 5000;
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

            Helpers\Wallet::createTransaction($user_guid, $points, $user_guid, "Email click. ($campaign)");
        }
        $cacher->set("rewarded:email:$campaign:$user_guid", true, strtotime('tomorrow', time()) - time());
    }
}
