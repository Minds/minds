<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

<<<<<<< HEAD
//var_dump(new Minds\entities\user('mark'));
//login(new Minds\entities\user('minds'));

$viv = new Minds\entities\user('viv');
Minds\plugin\search\start::createDocument($viv);
=======


$user = new Minds\entities\user('376005799496912896');
var_dump($user);
$user->save();
//$user->makeAdmin();
>>>>>>> master
