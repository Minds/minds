<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$entities = new Minds\Core\Data\Call('entities');
$indexes = new Minds\Core\Data\Call('entities_by_time');
$lu = new Minds\Core\Data\Call('user_index_to_guid');

echo "Encrypting emails \n";

$count = 0;
$offset = "";
while(true){
    $guids = $indexes->getRow('user', array('limit' => 200, 'offset' => $offset));
    $users = $entities->getRows(array_keys($guids), array('offset'=>'email', 'limit'=>1));
    if(count($guids) <= 1)
        break;

    echo "Encrypting";

    foreach($users as $guid => $data){
        $user = new Minds\Entities\User($data);
        $user->setEmail($user->getEmail());

        $entities->insert($guid, array('email'=>$user->email));
        if($user->getEmail())
            $lu->removeRow($user->getEmail());

        $count++;
        echo ".";
    }

    end($guids);
    $offset = key($guids);
    echo "Done up to $offset \n";

}
echo "\n Encrypted $count emails \n";
