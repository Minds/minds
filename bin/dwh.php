<?php
/**
 * Data warehouse
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
error_reporting(E_ALL);

switch($argv[1]){

    case "signups":
        echo "Collecting signups data \n";
        $db = new Minds\Core\Data\Call('entities_by_time');
        $limit = 1000;
        $offset = "";
        while(true){
            echo "[$offset]: ";
            $users = Minds\Core\Entities::get(['type'=>'user', 'limit' => $limit, 'offset'=>$offset]);
            foreach($users as $user){
                $ts = Minds\Core\Analytics\Timestamps::get(['day', 'month'], $user->time_created);
                $db->insert("analytics:signup:day:{$ts['day']}", [$user->guid => $user->time_created]);
                $db->insert("analytics:signup:month:{$ts['month']}", [$user->guid => $user->time_created]);
            }
            if(count($users) < $limit){
                break;
            }
            $offset = end($users)->guid;
            echo date('d-m-Y h:i', end($users)->time_created) . "\n";
            //break;
        }
        echo "Done \n";
        break;

    case "retention":
        echo "Calculating retention rates.. this may take a moment \n";
        $app = Minds\Core\Analytics\App::_()
          ->setMetric('retention')
          ->increment();
        echo "Done \n";
        break;
    case "cron":
        break;

}

