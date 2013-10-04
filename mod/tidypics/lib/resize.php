<?php
/**
 * Elgg tidypics library of resizing functions
 *
 * @package TidypicsImageResize
 */

include dirname(__FILE__) . "/watermark.php";


/**
 * Create thumbnails using PHP GD Library
 *
 * @param ElggFile holds the image that was uploaded
 * @param string   folder to store thumbnail in
 * @param string   name of the thumbnail
 * @return bool    TRUE on success
 */
function tp_create_gd_thumbnails($file, $prefix, $filestorename) {
	global $CONFIG;

	$image_sizes = elgg_get_plugin_setting('image_sizes', 'tidypics');
	if (!$image_sizes) {
		// move this out of library
		register_error(elgg_echo('tidypics:nosettings'));
		forward(REFERER);
		return FALSE;
	}
	$image_sizes = is_array($image_sizes) ?  $image_sizes :  unserialize($image_sizes);

	$thumb = new ElggFile();
	$thumb->owner_guid = $file->owner_guid;
	$thumb->container_guid = $file->container_guid;

	// tiny thumbail
	$thumb->setFilename($prefix."thumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_gd_resize(	$file->getFilenameOnFilestore(),
								$thumbname,
								FALSE,
								$image_sizes['tiny_image_width'],
								$image_sizes['tiny_image_height'],
								TRUE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->thumbnail = $prefix."thumb".$filestorename;

	// album thumbnail
	global $CONFIG;
	$CONFIG->debug = 'WARNING';
	$thumb->setFilename($prefix."smallthumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_gd_resize(	$file->getFilenameOnFilestore(),
								$thumbname,
								FALSE,
								$image_sizes['small_image_width'],
								$image_sizes['small_image_height'],
								TRUE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->smallthumb = $prefix."smallthumb".$filestorename;
	unset($CONFIG->debug);

	// main image
	$thumb->setFilename($prefix."largethumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_gd_resize(	$file->getFilenameOnFilestore(),
								$thumbname,
								TRUE,
								$image_sizes['large_image_width'],
								$image_sizes['large_image_height'],
								FALSE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->largethumb = $prefix."largethumb".$filestorename;

	$file->save(); // wasn't saving!
	unset($thumb);

	return TRUE;
}

/**
 * Writes resized version of an already uploaded image - original from Elgg filestore.php
 * Saves it in the same format as uploaded
 *
 * @param string $input_name The name of the file on the disk
 * @param string $output_name The name of the file to be written
 * @param bool - watermark this image?
 * @param int $maxwidth The maximum width of the resized image
 * @param int $maxheight The maximum height of the resized image
 * @param TRUE|FALSE $square If set to TRUE, will take the smallest of maxwidth and maxheight and use it to set the dimensions on all size; the image will be cropped.
 * @return bool TRUE on success or FALSE on failure
 */
function tp_gd_resize($input_name, $output_name, $watermark, $maxwidth, $maxheight, $square = FALSE, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0) {

	// Get the size information from the image
	$imgsizearray = getimagesize($input_name);
	if (!$imgsizearray) {
		return FALSE;
	}

	// Get width and height of image
	$width = $imgsizearray[0];
	$height = $imgsizearray[1];

	$params = tp_im_calc_resize_params($width, $height, $maxwidth, $maxheight, $square, $x1, $y1, $x2, $y2);
	if (!$params) {
		return FALSE;
	}
	
	$new_width = $params['new_width'];
	$new_height = $params['new_height'];
	$region_width = $params['region_width'];
	$region_height = $params['region_height'];
	$widthoffset = $params['width_offset'];
	$heightoffset = $params['height_offset'];

	$accepted_formats = array(
			'image/jpeg' => 'jpeg',
			'image/pjpeg' => 'jpeg',
			'image/png' => 'png',
			'image/x-png' => 'png',
			'image/gif' => 'gif'
	);

	// make sure the function is available
	$function = "imagecreatefrom" . $accepted_formats[$imgsizearray['mime']];
	if (!is_callable($function)) {
		return FALSE;
	}

	// load old image
	$oldimage = $function($input_name);
	if (!$oldimage) {
		return FALSE;
	}

	// allocate the new image
	$newimage = imagecreatetruecolor($new_width, $new_height);
	if (!$newimage) {
		return FALSE;
	}

	$rtn_code = imagecopyresampled(	$newimage,
									$oldimage,
									0,
									0,
									$widthoffset,
									$heightoffset,
									$new_width,
									$new_height,
									$region_width,
									$region_height);
	if (!$rtn_code) {
		return $rtn_code;
	}

	if ($watermark) {
		tp_gd_watermark($newimage);
	}

	switch ($imgsizearray['mime']) {
		case 'image/jpeg':
		case 'image/pjpeg':
			$rtn_code = imagejpeg($newimage, $output_name, 85);
			break;
		case 'image/png':
		case 'image/x-png':
			$rtn_code = imagepng($newimage, $output_name);
			break;
		case 'image/gif':
			$rtn_code = imagegif($newimage, $output_name);
			break;
	}

	imagedestroy($newimage);
	imagedestroy($oldimage);

	return $rtn_code;
}


/**
 * Create thumbnails using PHP imagick extension
 *
 * @param ElggFile holds the image that was uploaded
 * @param string   folder to store thumbnail in
 * @param string   name of the thumbnail
 * @return bool    TRUE on success
 */
function tp_create_imagick_thumbnails($file, $prefix, $filestorename) {
	$image_sizes = elgg_get_plugin_setting('image_sizes', 'tidypics');
	if (!$image_sizes) {
		register_error(elgg_echo('tidypics:nosettings'));
		return FALSE;
	}
	$image_sizes = unserialize($image_sizes);

	$thumb = new ElggFile();
	$thumb->owner_guid = $file->owner_guid;
	$thumb->container_guid = $file->container_guid;

	// tiny thumbnail
	$thumb->setFilename($prefix."thumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_imagick_resize(	$file->getFilenameOnFilestore(),
									$thumbname,
									$image_sizes['tiny_image_width'],
									$image_sizes['tiny_image_height'],
									TRUE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->thumbnail = $prefix."thumb".$filestorename;

	// album thumbnail
	$thumb->setFilename($prefix."smallthumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_imagick_resize(	$file->getFilenameOnFilestore(),
									$thumbname,
									$image_sizes['small_image_width'],
									$image_sizes['small_image_height'],
									TRUE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->smallthumb = $prefix."smallthumb".$filestorename;

	// main image
	$thumb->setFilename($prefix."largethumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_imagick_resize(	$file->getFilenameOnFilestore(),
									$thumbname,
									$image_sizes['large_image_width'],
									$image_sizes['large_image_height'],
									FALSE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->largethumb = $prefix."largethumb".$filestorename;

	tp_imagick_watermark($thumbname);

	unset($thumb);

	return TRUE;
}


/**
 * Resize using PHP imagick extension
 *
 * Writes resized version of an already uploaded image
 *
 *
 * @param string $input_name The name of the file input field on the submission form
 * @param string $output_name The name of the file to be written
 * @param int $maxwidth The maximum width of the resized image
 * @param int $maxheight The maximum height of the resized image
 * @param TRUE|FALSE $square If set to TRUE, will take the smallest of maxwidth and maxheight and use it to set the dimensions on all size; the image will be cropped.
 * @return bool TRUE on success
 */
function tp_imagick_resize($input_name, $output_name, $maxwidth, $maxheight, $square = FALSE, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0) {

	// Get the size information from the image
	$imgsizearray = getimagesize($input_name);
	if (!$imgsizearray) {
		return FALSE;
	}

	// Get width and height
	$width = $imgsizearray[0];
	$height = $imgsizearray[1];
	
	$params = tp_im_calc_resize_params($width, $height, $maxwidth, $maxheight, $square, $x1, $y1, $x2, $y2);
	if (!$params) {
		return FALSE;
	}
	
	$new_width = $params['new_width'];
	$new_height = $params['new_height'];
	$region_width = $params['region_width'];
	$region_height = $params['region_height'];
	$widthoffset = $params['width_offset'];
	$heightoffset = $params['height_offset'];

	try {
		$img = new Imagick($input_name);
	} catch (ImagickException $e) {
		return FALSE;
	}

	$img->cropImage($region_width, $region_height, $widthoffset, $heightoffset);

	// use the default IM filter (windowing filter), I think 1 means default blurring or number of lobes
	$img->resizeImage($new_width, $new_height, imagick::FILTER_LANCZOS, 1);
	$img->setImagePage($new_width, $new_height, 0, 0);

	if ($img->writeImage($output_name) != TRUE) {
		$img->destroy();
		return FALSE;
	}

	$img->destroy();

	return TRUE;
}

/**
 * Create thumbnails using ImageMagick executables
 *
 * @param ElggFile holds the image that was uploaded
 * @param string   folder to store thumbnail in
 * @param string   name of the thumbnail
 * @return bool    TRUE on success
 */
function tp_create_im_cmdline_thumbnails($file, $prefix, $filestorename) {
	$image_sizes = elgg_get_plugin_setting('image_sizes', 'tidypics');
	if (!$image_sizes) {
		register_error(elgg_echo('tidypics:nosettings'));
		return FALSE;
	}
	$image_sizes = unserialize($image_sizes);

	$thumb = new ElggFile();
	$thumb->owner_guid = $file->owner_guid;
	$thumb->container_guid = $file->container_guid;

	// tiny thumbnail
	$thumb->setFilename($prefix."thumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_im_cmdline_resize(	$file->getFilenameOnFilestore(),
										$thumbname,
										$image_sizes['tiny_image_width'],
										$image_sizes['tiny_image_height'],
										TRUE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->thumbnail = $prefix."thumb".$filestorename;


	// album thumbnail
	$thumb->setFilename($prefix."smallthumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_im_cmdline_resize(	$file->getFilenameOnFilestore(),
										$thumbname,
										$image_sizes['small_image_width'],
										$image_sizes['small_image_height'],
										TRUE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->smallthumb = $prefix."smallthumb".$filestorename;

	// main image
	$thumb->setFilename($prefix."largethumb".$filestorename);
	$thumbname = $thumb->getFilenameOnFilestore();
	$rtn_code = tp_im_cmdline_resize(	$file->getFilenameOnFilestore(),
										$thumbname,
										$image_sizes['large_image_width'],
										$image_sizes['large_image_height'],
										FALSE);
	if (!$rtn_code) {
		return FALSE;
	}
	$file->largethumb = $prefix."largethumb".$filestorename;


	tp_im_cmdline_watermark($thumbname);

	unset($thumb);

	return TRUE;
}

/**
 * Gets the jpeg contents of the resized version of an already uploaded image
 * (Returns FALSE if the uploaded file was not an image)
 *
 * @param string $input_name The name of the file input field on the submission form
 * @param string $output_name The name of the file to be written
 * @param int $maxwidth The maximum width of the resized image
 * @param int $maxheight The maximum height of the resized image
 * @param TRUE|FALSE $square If set to TRUE, will take the smallest of maxwidth and maxheight and use it to set the dimensions on all size; the image will be cropped.
 * @return bool
 */
function tp_im_cmdline_resize($input_name, $output_name, $maxwidth, $maxheight, $square = FALSE, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0) {


	// Get the size information from the image
	$imgsizearray = getimagesize($input_name);
	if (!$imgsizearray) {
		return FALSE;
	}

	// Get width and height
	$orig_width = $imgsizearray[0];
	$orig_height = $imgsizearray[1];

	$params = tp_im_calc_resize_params($orig_width, $orig_height, $maxwidth, $maxheight, $square, $x1, $y1, $x2, $y2);
	if (!$params) {
		return FALSE;
	}

	$newwidth = $params['new_width'];
	$newheight = $params['new_height'];
	
	$accepted_formats = array(
			'image/jpeg' => 'jpeg',
			'image/pjpeg' => 'jpeg',
			'image/png' => 'png',
			'image/x-png' => 'png',
			'image/gif' => 'gif'
	);

	// If it's a file we can manipulate ...
	if (!array_key_exists($imgsizearray['mime'],$accepted_formats)) {
		return FALSE;
	}

	$im_path = elgg_get_plugin_setting('im_path', 'tidypics');
	if (!$im_path) {
		$im_path = "/usr/bin/";
	}
	if (substr($im_path, strlen($im_path)-1, 1) != "/") {
		$im_path .= "/";
	}

	// see imagemagick web site for explanation of these parameters
	// the ^ in the resize means those are minimum width and height values
	$command = $im_path . "convert \"$input_name\" -resize ".$newwidth."x".$newheight."^ -gravity center -extent ".$newwidth."x".$newheight." \"$output_name\"";
	$output = array();
	$ret = 0;
	exec($command, $output, $ret);
	if ($ret == 127) {
		trigger_error('Tidypics warning: Image Magick convert is not found', E_USER_WARNING);
		return FALSE;
	} else if ($ret > 0) {
		trigger_error('Tidypics warning: Image Magick convert failed', E_USER_WARNING);
		return FALSE;
	}
	
	return TRUE;
}

/**
 * Calculate the resizing/cropping parameters
 *
 * @param int $orig_width
 * @param int $orig_height
 * @param int $new_width
 * @param int $new_height
 * @param bool $square
 * @param int $x1
 * @param int $y1
 * @param int $x2
 * @param int $y2
 * @return array|FALSE
 */
function tp_im_calc_resize_params($orig_width, $orig_height, $new_width, $new_height, $square = FALSE, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0) {
	// crop image first?
	$crop = TRUE;
	if ($x1 == 0 && $y1 == 0 && $x2 == 0 && $y2 == 0) {
		$crop = FALSE;
	}

	// how large a section of the image has been selected
	if ($crop) {
		$region_width = $x2 - $x1;
		$region_height = $y2 - $y1;
	} else {
		// everything selected if no crop parameters
		$region_width = $orig_width;
		$region_height = $orig_height;
	}

	// determine cropping offsets
	if ($square) {
		// asking for a square image back

		// detect case where someone is passing crop parameters that are not for a square
		if ($crop == TRUE && $region_width != $region_height) {
			return FALSE;
		}

		// size of the new square image
		$new_width = $new_height = min($new_width, $new_height);

		// find largest square that fits within the selected region
		$region_width = $region_height = min($region_width, $region_height);

		// set offsets for crop
		if ($crop) {
			$widthoffset = $x1;
			$heightoffset = $y1;
			$orig_width = $x2 - $x1;
			$orig_height = $orig_width;
		} else {
			// place square region in the center
			$widthoffset = floor(($orig_width - $region_width) / 2);
			$heightoffset = floor(($orig_height - $region_height) / 2);
		}
	} else {
		// non-square new image

		// maintain aspect ratio of original image/crop
		if (($region_height / (float)$new_height) > ($region_width / (float)$new_width)) {
			$new_width = floor($new_height * $region_width / (float)$region_height);
		} else {
			$new_height = floor($new_width * $region_height / (float)$region_width);
		}

		// by default, use entire image
		$widthoffset = 0;
		$heightoffset = 0;

		if ($crop) {
			$widthoffset = $x1;
			$heightoffset = $y1;
		}
	}

	$resize_params = array();
	$resize_params['new_width'] = $new_width;
	$resize_params['new_height'] = $new_height;
	$resize_params['region_width'] = $region_width;
	$resize_params['region_height'] = $region_height;
	$resize_params['width_offset'] = $widthoffset;
	$resize_params['height_offset'] = $heightoffset;

	return $resize_params;
}
