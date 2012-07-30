<?php
/**
 * Watermarking functions
 *
 * @package TidypicsWatermark
 */

/**
 * Make replacements in watermark text
 *
 * @param string $text
 * @param ElggUser $owner
 * @return string
 */
function tp_process_watermark_text($text, $owner) {
	global $CONFIG;

	$text = str_replace("%name%", $owner->name, $text);
	$text = str_replace("%sitename%", $CONFIG->sitename, $text);

	return $text;
}

/**
 * Create the watermark image filename
 *
 * @param string $text
 * @param ElggUser $owner
 * @return string
 */
function tp_get_watermark_filename($text, $owner) {

	$base = elgg_strtolower($text);
	$base = preg_replace("/[^\w-]+/", "-", $base);
	$base = trim($base, '-');

	$filename = tp_get_img_dir();
	$filename .= elgg_strtolower($owner->username . "_" . $base . "_stamp");

	return $filename;
}

/**
 * Use GD to apply watermark to image
 *
 * @param resource $image GD image resource
 */
function tp_gd_watermark($image) {
	global $CONFIG;
	
	$watermark_text = elgg_get_plugin_setting('watermark_text', 'tidypics');
	if (!$watermark_text) {
		return;
	}

	// plugins can do their own watermark and return false to prevent this function from running
	if (elgg_trigger_plugin_hook('tp_watermark', 'gd', $image, true) === false) {
		return;
	}

	$owner = elgg_get_logged_in_user_entity();

	$watermark_text = tp_process_watermark_text($watermark_text, $owner);

	// transparent gray
	imagealphablending($image, true);
	$textcolor = imagecolorallocatealpha($image, 50, 50, 50, 60);

	// font and location
	$font = $CONFIG->pluginspath . "tidypics/fonts/LiberationSerif-Regular.ttf";
	$bbox = imagettfbbox(20, 0, $font, $watermark_text);

	$text_width = $bbox[2] - $bbox[0];
	$text_height = $bbox[1] - $bbox[7];

	$image_width = imagesx($image);
	$image_height = imagesy($image);

	$left = $image_width / 2 - $text_width / 2;
	$top = $image_height - 20;

	// write the text on the image
	imagettftext($image, 20, 0, $left, $top, $textcolor, $font, $watermark_text);
}

/**
 * imagick watermarking
 *
 * @param string $filename
 * @return bool
 */
function tp_imagick_watermark($filename) {

	$watermark_text = elgg_get_plugin_setting('watermark_text', 'tidypics');
	if (!$watermark_text) {
		return false;
	}

	// plugins can do their own watermark and return false to prevent this function from running
	if (elgg_trigger_plugin_hook('tp_watermark', 'imagick', $filename, true) === false) {
		return true;
	}

	$owner = elgg_get_logged_in_user_entity();

	$watermark_text = tp_process_watermark_text($watermark_text, $owner);

	$img = new Imagick($filename);

	$img->readImage($image);

	$draw = new ImagickDraw();

	//$draw->setFont("");

	$draw->setFontSize(28);

	$draw->setFillOpacity(0.5);

	$draw->setGravity(Imagick::GRAVITY_SOUTH);

	$img->annotateImage($draw, 0, 0, 0, $watermark_text);

	if ($img->writeImage($filename) != true) {
		$img->destroy();
		return false;
	}

	$img->destroy();

	return true;
}

/**
 * ImageMagick watermarking
 *
 * @param string $filename
 */
function tp_im_cmdline_watermark($filename) {

	$watermark_text = elgg_get_plugin_setting('watermark_text', 'tidypics');
	if (!$watermark_text) {
		return;
	}

	// plugins can do their own watermark and return false to prevent this function from running
	if (elgg_trigger_plugin_hook('tp_watermark', 'imagemagick', $filename, true) === false) {
		return;
	}

	$im_path = elgg_get_plugin_setting('im_path', 'tidypics');
	if (!$im_path) {
		$im_path = "/usr/bin/";
	}

	// make sure end of path is /
	if (substr($im_path, strlen($im_path)-1, 1) != "/") {
		$im_path .= "/";
	}


	$owner = elgg_get_logged_in_user_entity();

	$watermark_text = tp_process_watermark_text($watermark_text, $owner);

	$ext = ".png";

	$user_stamp_base = tp_get_watermark_filename($watermark_text, $owner);


	if ( !file_exists( $user_stamp_base . $ext )) {
		//create the watermark image if it doesn't exist
		$commands = array();
		$commands[] = $im_path . 'convert -size 300x50 xc:grey30 -pointsize 20 -gravity center -draw "fill grey70  text 0,0  \''. $watermark_text . '\'" "'. $user_stamp_base . '_fgnd' . $ext . '"';
		$commands[] = $im_path . 'convert -size 300x50 xc:black -pointsize 20 -gravity center -draw "fill white  text  1,1  \''. $watermark_text . '\' text  0,0  \''. $watermark_text . '\' fill black  text -1,-1 \''. $watermark_text . '\'" +matte ' . $user_stamp_base . '_mask' . $ext;
		$commands[] = $im_path . 'composite -compose CopyOpacity  "' . $user_stamp_base . "_mask" . $ext . '" "' . $user_stamp_base . '_fgnd' . $ext . '" "' . $user_stamp_base . $ext . '"';
		$commands[] = $im_path . 'mogrify -trim +repage "' . $user_stamp_base . $ext . '"';
		$commands[] = 'rm "' . $user_stamp_base . '_mask' . $ext . '"';
		$commands[] = 'rm "' . $user_stamp_base . '_fgnd' . $ext . '"';

		foreach( $commands as $command ) {
			exec( $command );
		}
	}

	//apply the watermark
	$commands = array();
	$commands[] = $im_path . 'composite -gravity south -geometry +0+10 "' . $user_stamp_base . $ext . '" "' . $filename . '" "' . $filename . '_watermarked"';
	$commands[] = "mv \"$filename" . "_watermarked\" \"$filename\"";
	foreach( $commands as $command ) {
		exec( $command );
	}
}
