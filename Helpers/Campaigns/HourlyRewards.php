<?php
namespace Minds\Helpers\Campaigns;

use Minds\Core;
use Minds\Helpers;

/**
 * Helper for daily rewards
 * @todo Avoid static method and user proper DI.
 */
class HourlyRewards
{
    /**
     * Grants daily reward and sends notification to current user
     * @return boolean|null
     */
    public static function reward()
    {
        if (!Core\Session::isLoggedin()) {
            return false;
        }

        $ts = Helpers\Analytics::buildTS("hour", time());
        $cacher = Core\Data\cache\factory::build('apcu');
        if ($cacher->get("rewarded:$ts:" . Core\Session::getLoggedinUser()->guid) == true) {
            return;
        }

        //CAMPAIGN:: Reward 10 points per day if a user opens their app
        $db = new Core\Data\Call('entities_by_time');

        $row = $db->getRow("analytics:rewarded:hourly:$ts", array('offset'=>Core\Session::getLoggedinUser()->guid, 'limit'=>1));
        if (!$row || key($row) != Core\Session::getLoggedinUser()->guid) {
            $db->insert("analytics:rewarded:hourly:$ts", array(Core\Session::getLoggedinUser()->guid => time()));

            Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, 20, Core\Session::getLoggedinUser()->guid, "Hourly reward.");
            $message = "You have received 20 points as an hourly reward. Stick around receive more points!";
            Core\Events\Dispatcher::trigger('notification', 'dailyReward', array(
              'to'=>array(Core\Session::getLoggedinUser()->guid),
              'from' => 100000000000000519,
              'notification_view' => 'custom_message',
              'params' => array('message'=>$message),
              'message'=>$message
              ));
        }
        $cacher->set("rewarded:$ts:" . Core\Session::getLoggedinUser()->guid, true, strtotime('tomorrow', time()) - time());
    }
}
