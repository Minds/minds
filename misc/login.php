<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

use Minds\Core;
use Minds\Core\Data;

var_dump(new Minds\entities\user('collectiveevolution'));
exit;

//login(new Minds\entities\user('minds'));


$prepared = new Core\Data\Neo4j\Prepared\Common();
$result= Core\Data\Client::build('Neo4j')->request($prepared->getTrendingObjects('image', 30));
$rows = $result->getRows();

$guids = array();
foreach($rows['object'] as $k => $object){
    $guids[$object['guid']] = $rows['c'][$k];
    echo "guid = " . $object['guid'] . "\n";
    $actual = \Minds\Helpers\Counters::get($object['guid'], "thumbs:up", false);
    echo "actual= $actual \n"; 
    echo "neo4j thinks= " . $rows['c'][$k] . "\n";
    $new =  $rows['c'][$k] - $actual;
    echo "incrementing to " . $new . " \n\n\n";
    \Minds\Helpers\Counters::increment($object['guid'], "thumbs:up", $new);
} 

exit;




$user_guid = 100000000000000519;
$db = new Minds\Core\Data\Call('entities_by_time');
$offset = "";


while(true){

    $user_guids = $db->getRow("user", array('limit'=>2000, 'offset'=>$offset));
    echo "$offset \n";
    foreach($user_guids as $guid => $meta){
        if($guid != $user_guid)
            $db->removeAttributes("activity:user:$guid", array("421981950383755264"));
        $db->insert("activity:network:$guid", array("421981950383755264"=>"421981950383755264"));
    }

    end($user_guids);
    $offset = key($user_guids);

}



/*
$prepared = new Data\Neo4j\Prepared\Common();
$result= Data\Client::build('Neo4j')->request($prepared->getActed(array_keys($boosts), "100000000000000063"));
$rows = $result->getRows();
foreach($rows['items'] as $item){
    echo $item['guid'];
}
exit;
 */
$user_guid = "100000000000091314";
$db = new Minds\Core\Data\Call('entities_by_time');
$db2 = new Minds\Core\Data\Call('entities');
$guids = $db->getRow("object:video:user:$user_guid");
$db->insert("object:archive:user:$user_guid", $guids);
exit;

$i = 0;
foreach($guids as $guid){
    echo "$guid found \n";
    if(!$db2->getRow($guid)){
        echo "$guid doesn't exist \n";
    ///    $db->removeAttributes("activity:user:$user_guid", array($guid));
    }       
}
exit;
var_dump($guids); exit;


/*
$notification = Minds\entities\Factory::build(427462132519407616);
var_dump($notification);
 */
$viv = new Minds\entities\user('markna');
var_dump($viv);

$viv->salt = generate_random_cleartext_password(); // Reset the salt
$viv->password = generate_user_password($viv, 'temp123', 'sha256');
$viv->override_password = true;
$viv->save();
//login($viv);
