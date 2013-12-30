<?php 

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$owner_guid = get_input('guid');
$owner = get_entity($owner_guid, 'user');
$thumb = get_input('thumb');


if($owner->background){
	
	header('Content-Type: image/jpeg');
	header('Expires: ' . date('r', time() + 864000));
	header("Pragma: public");
	header("Cache-Control: public");
		
	$file = new ElggFile;
	$file->owner_guid = $owner_guid;
		if($thumb == 'yes')
			$file->setFilename('profile/background_thumb.jpg');
		else
			$file->setFilename('profile/background.jpg');
		if($file->exists())
			echo $file->grabFile();
}
