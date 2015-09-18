<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$entities = new Minds\Core\Data\Call('entities');
$indexes = new Minds\Core\Data\Call('entities_by_time');
$lu = new Minds\Core\Data\Call('user_index_to_guid');

echo "Sending push reward notification \n";

$config = new Surge\Config(array(
     'Apple' => array(
         'cert'=> '/var/secure/apns-production.pem'
      //   'sandbox'=>true,
      //   'cert'=> '/var/secure/apns.pem'
     ),
     'Google' => array(
         'api_key' => 'AIzaSyCp0LVJLY7SzTlxPqVn2-2zWZXQKb1MscQ'
     )));

$count = 0;
$offset = isset($argv[1]) ? $argv[1] : "";
while(true){
    $guids = $indexes->getRow('user', array('limit' => 1000, 'offset' => $offset));
    $users = $entities->getRows(array_keys($guids), array('offset'=>'surge_token', 'limit'=>1));
    if(count($guids) <= 1)
        break;

    echo "Notifying ";

    foreach($users as $guid => $data){
        if(!isset($data['surge_token']))
          continue;

        $msg = "Upgrade today to try encrypted video chat ;-)";
        $message = Surge\Messages\Factory::build($data['surge_token'])
            ->setTitle($msg)
            ->setMessage($msg)
            ->setURI('newsfeed')
            ->setSound('default');

        Surge\Surge::send($message, $config);

        $count++;
        echo ".";
    }

    end($guids);
    $offset = key($guids);
    echo "Done up to $offset \n";

}
echo "\n Sent $count push notifications \n";

