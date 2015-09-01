<?php
namespace Minds\Helpers\Campaigns;

use Minds\Core;
use Minds\Helpers;

class DailyRewards{

    static public function reward(){
        $cacher = Core\Data\cache\factory::build('apcu');
        if($cacher->get('rewarded:' . Core\session::getLoggedinUser()->guid) == true)
            return;

        //CAMPAIGN:: Reward 10 points per day if a user opens their app
        $db = new Core\Data\Call('entities_by_time');
        $ts = Helpers\Analytics::buildTS("day", time());
        $row = $db->getRow("analytics:rewarded:day:$ts", array('offset'=>Core\session::getLoggedinUser()->guid, 'limit'=>1));
        if(!$row || key($row) != Core\session::getLoggedinUser()->guid){
          $db->insert("analytics:rewarded:day:$ts", array(Core\session::getLoggedinUser()->guid => time()));

          \Minds\plugin\payments\start::createTransaction(Core\session::getLoggedinUser()->guid, 10, Core\session::getLoggedinUser()->guid, "Daily login reward.");
          Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
              'to'=>array(Core\session::getLoggedinUser()->guid),
              'from' => 100000000000000519,
              'notification_view' => 'custom_message',
              'params' => array('message'=>"We gave you 10 points for logging in!"),
              'message'=>"We gave you 10 points for logging in!"
              ));
        }
        $cacher->set('rewarded:' . Core\session::getLoggedinUser()->guid, true, strtotime('tomorrow', time()));
    }

}
