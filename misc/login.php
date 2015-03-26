<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

//var_dump(new Minds\entities\user('mark'));
//login(new Minds\entities\user('minds'));

  $db = new Minds\Core\Data\Call('entities_by_time');
$db->removeRow("boost:newsfeed");

$notification = Minds\entities\Factory::build(427462132519407616);
var_dump($notification);

$viv = new Minds\entities\user('ottman');
//var_dump($viv);
//login($viv);
