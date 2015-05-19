<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$db = new Minds\Core\Data\Call('entities_by_time');

$offset = "409370335293476864";
while(true){
    echo "Loading 2000 users ...";
    $users = $db->getRow('user', array('limit'=>500, 'offset'=>$offset));
    if($offset)
        unset($users[$offset]);
    end($users);
    $offset = key($users);
    echo "done (" . count($users) . ") \n";


    foreach($users as $user => $ts){

        echo "Sending push notification to @$user ...";
        
        \Minds\Core\Queue\Client::build()->setExchange("mindsqueue")
                                      ->setQueue("Push")
                                      ->send(array(
                                            "user_guid"=>$user,
                                            "message"=>"A new build (26) is now out.",
                                            "uri" => 'notification'
                                           ));

        echo "done \n";

    }

    echo "\n\n";
    sleep(1);
}

echo "Done \n";
