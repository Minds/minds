<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

use Minds\Core\Data;

//var_dump(new Minds\entities\user('mark'));
//login(new Minds\entities\user('minds'));


/*
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
 */
$viv = new Minds\entities\user('markna');
var_dump($viv);

$viv->salt = generate_random_cleartext_password(); // Reset the salt
$viv->password = generate_user_password($viv, 'temp123', 'sha256');
$viv->override_password = true;
$viv->save();
//login($viv);
