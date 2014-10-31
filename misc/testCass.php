<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');


$db = new minds\core\data\call('entities');
var_dump($db->getRow('373948046779617280'));

$db = new minds\core\data\call('entities_by_time');
var_dump($db->getRow('object:blog'));
exit;

foreach($db->get("",1000) as $k => $v){
var_dump($k);

}
