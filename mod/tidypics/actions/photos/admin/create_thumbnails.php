<?php
/**
 * Tidypics Thumbnail Creation Test
 *
 * Called through ajax, but registered as an Elgg action.
 * 
 */

elgg_load_library('tidypics:resize');

$guid = get_input('guid');
$image = get_entity($guid);

if (!$image || !($image instanceof TidypicsImage)) {
	register_error(elgg_echo('tidypics:thumbnail_tool:unknown_image'));
	forward(REFERER);
}

$filename = $image->getFilename();
$container_guid = $image->container_guid;
if (!$filename || !$container_guid) {
	register_error(elgg_echo('tidypics:thumbnail_tool:invalid_image_info'));
	forward(REFERER);
}

$title = $image->getTitle();
$prefix = "image/$container_guid/";
$filestorename = substr($filename, strlen($prefix));

$image_lib = elgg_get_plugin_setting('image_lib', 'tidypics');
if (!$image_lib) {
	$image_lib = "GD";
}

// ImageMagick command line
if ($image_lib == 'ImageMagick') {
	if (!tp_create_im_cmdline_thumbnails($image, $prefix, $filestorename)) {
		trigger_error('Tidypics warning: failed to create thumbnails - ImageMagick command line', E_USER_WARNING);
		register_error(elgg_echo('tidypics:thumbnail_tool:create_failed'));
		forward(REFERER);
	}

// imagick PHP extension
} else if ($image_lib == 'ImageMagickPHP') {  
	if (!tp_create_imagick_thumbnails($image, $prefix, $filestorename)) {
		trigger_error('Tidypics warning: failed to create thumbnails - ImageMagick PHP', E_USER_WARNING);
		register_error(elgg_echo('tidypics:thumbnail_tool:create_failed'));
		forward(REFERER);
	}
// gd
} else {
	if (!tp_create_gd_thumbnails($image, $prefix, $filestorename)) {
		trigger_error('Tidypics warning: failed to create thumbnails - GD', E_USER_WARNING);
		register_error(elgg_echo('tidypics:thumbnail_tool:create_failed'));
		forward(REFERER);
	}
}

$url = elgg_normalize_url("photos/thumbnail/$guid/large");
system_message(elgg_echo('tidypics:thumbnail_tool:created'));

if (elgg_is_xhr()) {
	echo json_encode(array(
		'guid' => $guid,
		'title' => $title,
		'thumbnail_src' => $url
	));
}

forward(REFERER);