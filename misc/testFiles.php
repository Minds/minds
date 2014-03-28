<?php

require(dirname(dirname(__FILE__)) . '/engine/start.php');

$user = get_user_by_username('mark');
$guid = 63;
$user_path = date('Y/m/d/', $user->time_created) . $guid;
$data_root = $CONFIG->dataroot;

$filename = "$data_root$user_path/profile/{$guid}master.jpg";
$contents = @file_get_contents($filename);

//echo $filename;
//exit;
if (!empty($contents)) {
			header("Content-type: image/jpeg");
			header('Expires: ' . date('r',  strtotime("today+6 months")), true);
			header("Pragma: public");
			header("Cache-Control: public");
			header("Content-Length: " . strlen($contents));
			header("ETag: $etag");
			//header("X-No-Client-Cache:0");
			//// this chunking is done for supposedly better performance
			//$split_string = str_split($contents, 1024);
			//foreach ($split_string as $chunk) {
			//	echo $chunk;
		//	}
			echo $contents;
			exit;
		}
