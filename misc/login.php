<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

//var_dump(new Minds\entities\user('mark'));
//login(new Minds\entities\user('minds'));

$viv = new Minds\entities\user('viv');
Minds\plugin\search\start::createDocument($viv);
