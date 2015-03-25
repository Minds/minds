<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");



$user = new Minds\entities\user('376005799496912896');
var_dump($user);
$user->save();
//$user->makeAdmin();
