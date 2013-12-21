<?php

$src = urldecode(get_input('src'));

                        header('Expires: ' . date('r',  strtotime("today+6 months")), true);
                        header("Pragma: public");
header("Cache-Control: public");
header("X-No-Client-Cache:0");

// Get new dimensions
list($width, $height) = getimagesize($src);
$new_width = get_input('width', 400);
$ratio = $width / $height;
$new_height = $new_width / $ratio;

if($width <= 1 || $height <= 1){
	$new_width = 1;
	$new_height = 1;
}

// Resample
$image_p = imagecreatetruecolor($new_width, $new_height);

$mime = getimagesize($src);
$mime = $mime['mime'];
switch($mime){
	case 'image/gif':
	//$image = imagecreatefromgif($src);
	//WE WANT TO HAVE COOL GIFS!
//	header('Content-type: image/gif');
//	readfile($src);
	forward($src);
	return;
	break;
	case 'image/png':
	$image = imagecreatefrompng($src);
	break;
	case 'image/bmp':
	case 'image/jpeg':
	default:
	$image = imagecreatefromjpeg($src);
}
if(!$image){
	//we couldn't get the images, just output directly
	//header('Content-type: image/jpeg');
	forward($src); 
	return;
}
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

header('Content-type: image/jpeg');
// Output
imagejpeg($image_p, null, 100);
exit;
