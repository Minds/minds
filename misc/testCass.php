<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');


$db = new minds\core\data\call('entities_by_time', 'minds', array('10.0.9.10'));
var_dump($db->getRow('object', array('limit'=>10)));
