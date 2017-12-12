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

        $wire = false;

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
          case "december-11":
            $validator = $_GET['validator'];
            if ($validator == sha1($campaign . $user->guid . Config::_()->get('emails_secret'))) {
                $points = 2500;
                $wire = true;
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

            if ($wire) {
                $plus = new Entities\User('730071191229833224');
                $service = Core\Wire\Methods\Factory::build('points');                
                $service->setAmount($points)
                    ->setEntity($user)
                    ->setFrom($plus)
                    ->create();
                Core\Queue\Client::build()->setQueue("WireNotification")
                  ->send(array(
                    "amount" => $points,
                    "sender" => serialize($plus),
                    "entity" => serialize($user),
                    "method" => 'points',
                    "subscribed" => false 
                   ));
            } else {
                Helpers\Wallet::createTransaction($user_guid, $points, $user_guid, "Email Click ($label)");
            }
        }
        $cacher->set("rewarded:email:$campaign:$user_guid", true, strtotime('tomorrow', time()) - time());
    }
}
