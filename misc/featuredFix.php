<?php

require('/var/www/elgg/engine/start.php');

$db = new minds\core\data\call('entities_by_time');
$videos = $db->getRow('object:video:featured', array('limit'=>100000));
$images = $db->getRow('object:image:featured', array('limit'=>100000));
$albums = $db->getRow('object:album:featured', array('limit'=>100000));

//$db->removeRow('object:archive:featured');
//$guids = array_merge($videos, $images, $albums);
foreach($albums as $id => $guid){
	echo "$id \n";
	echo $db->insert('object:archive:featured', array($id => $guid));
}
