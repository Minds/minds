<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

foreach(array("100000000000000063", "100000000000000341", "100000000000000134", "100000000000000202", "100000000000000793") as $user_guid){

    $db = new Minds\Core\Data\Call('entities_by_time');
    $activity = $db->getRow("activity:network:$user_guid");
    foreach($activity as $guid => $data){
        if(Minds\entities\Factory::build($guid)->type != "activity"){
                    echo "remove $guid \n";
                $db->removeAttributes("activity:network:$user_guid", array($guid));
        }
    }

}
