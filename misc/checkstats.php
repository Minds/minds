<?php

require_once(dirname(dirname(__FILE__)) .'/engine/start.php');

$db = new minds\core\data\call('entities_by_time');

$kaltura = $db->countRow('object:kaltura_video');
$cinemr = $db->countRow('object:video');

var_dump(array(

	'kaltura' => $kaltura,
	'cinemr' => $cinemr

));
