<?php 

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

header('Content-Type: image/jpeg');

$owner_guid = get_input('guid');
$thumb = get_input('thumb');


if($owner_guid){
		
	$file = new ElggFile;
	$file->owner_guid = $owner_guid;
		if($thumb == 'yes')
			$file->setFilename('profile/background_thumb.jpg');
		else
			$file->setFilename('profile/background.jpg');
		if($file->exists())
			echo $file->grabFile();
}
