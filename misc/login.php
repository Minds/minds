<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

use Minds\Core\Data;

//var_dump(new Minds\entities\user('mark'));
//login(new Minds\entities\user('minds'));

$db = new Data\Call('entities_by_time');
$boosts = $db->getRow("boost:suggested", array('limit'=>15));
var_dump(array_keys($boosts));

$prepared = new Data\Neo4j\Prepared\Common();
$result= Data\Client::build('Neo4j')->request($prepared->getActed(array_keys($boosts), "100000000000000063"));
$rows = $result->getRows();
foreach($rows['items'] as $item){
    echo $item['guid'];
}
exit;
$db = new Minds\Core\Data\Call('entities_by_time');
$db->removeRow("boost:newsfeed");

$notification = Minds\entities\Factory::build(427462132519407616);
var_dump($notification);

$viv = new Minds\entities\user('ottman');
//var_dump($viv);
//login($viv);
