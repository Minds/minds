<?php
namespace Minds\Helpers\Campaigns;

use Minds\Core;
use Minds\Helpers;

class DailyRewards{

    static public function reward(){
        $cacher = Core\Data\cache\factory::build('apcu');
        if($cacher->get('rewarded:' . Core\Session::getLoggedinUser()->guid) == true)
            return;

        //CAMPAIGN:: Reward 10 points per day if a user opens their app
        $db = new Core\Data\Call('entities_by_time');
        $ts = Helpers\Analytics::buildTS("day", time());
        $row = $db->getRow("analytics:rewarded:day:$ts", array('offset'=>Core\Session::getLoggedinUser()->guid, 'limit'=>1));
        if(!$row || key($row) != Core\Session::getLoggedinUser()->guid){
          $db->insert("analytics:rewarded:day:$ts", array(Core\Session::getLoggedinUser()->guid => time()));

          \Minds\plugin\payments\start::createTransaction(Core\Session::getLoggedinUser()->guid, 10, Core\Session::getLoggedinUser()->guid, "Daily login reward.");
          $message = "You have received 10 points as a daily login reward.  Log back in again tomorrow to receive more points!";
          Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
              'to'=>array(Core\Session::getLoggedinUser()->guid),
              'from' => 100000000000000519,
              'notification_view' => 'custom_message',
              'params' => array('message'=>$message),
              'message'=>$message
              ));
        }
        $cacher->set('rewarded:' . Core\Session::getLoggedinUser()->guid, true, strtotime('tomorrow', time()) - time());
    }

}
