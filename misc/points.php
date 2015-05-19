<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$db = new Minds\Core\Data\Call('entities_by_time');

$offset = "";
while(true){
    echo "Loading 1000 users ...";
    $users = $db->getRow('user', array('limit'=>1000, 'offset'=>$offset));
    end($users);
    $offset = key($users);
    echo "done (" . count($users) . ") \n";


    foreach($users as $user => $ts){

        echo "Awarding points to @$user ...";
        \Minds\plugin\payments\start::createTransaction($user, 100, 0, "Loyalty Reward.");
        echo "done \n";

    }

    echo "\n\n";
}

echo "Done \n";
