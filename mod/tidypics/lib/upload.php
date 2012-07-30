<?php
/**
 * Helper library for working with uploads
 */

/**
 * Guess on the mimetype based on file extension
 *
 * @param string $originalName
 * @return string
 */
function tp_upload_get_mimetype($originalName) {
	$extension = substr(strrchr($originalName, '.'), 1);
	switch (strtolower($extension)) {
		case 'png':
			return 'image/png';
			break;
		case 'gif':
			return 'image/gif';
			break;
		case 'jpg':
		case 'jpeg':
			return 'image/jpeg';
			break;
		default:
			return 'unknown';
			break;
	}
}

/**
 * Check if this is an image
 * 
 * @param string $mime
 * @return bool false = not image
 */
function tp_upload_check_format($mime) {
	$accepted_formats = array(
		'image/jpeg',
		'image/png',
		'image/gif',
		'image/pjpeg',
		'image/x-png',
	);

	if (!in_array($mime, $accepted_formats)) {
		return false;
	}
	return true;
}

/**
 * Check if there is enough memory to process this image
 * 
 * @param string $image_lib
 * @param int $num_pixels
 * @return bool false = not enough memory
 */
function tp_upload_memory_check($image_lib, $num_pixels) {
	if ($image_lib !== 'GD') {
		return true;
	}

	$mem_avail = ini_get('memory_limit');
	$mem_avail = rtrim($mem_avail, 'M');
	$mem_avail = $mem_avail * 1024 * 1024;
	$mem_used = memory_get_usage();
	$mem_required = ceil(5.35 * $num_pixels);

	$mem_avail = $mem_avail - $mem_used - 2097152; // 2 MB buffer
	if ($mem_required > $mem_avail) {
		return false;
	}

	return true;
}

/**
 * Check if image is within limits
 *
 * @param int $image_size
 * @return bool false = too large
 */
function tp_upload_check_max_size($image_size) {
	$max_file_size = (float) elgg_get_plugin_setting('maxfilesize', 'tidypics');
	if (!$max_file_size) {
		// default to 5 MB if not set
		$max_file_size = 5;
	}
	// convert to bytes from MBs
	$max_file_size = 1024 * 1024 * $max_file_size;
	return $image_size <= $max_file_size;
}

/**
 * Check if this image pushes user over quota
 *
 * @param int $image_size
 * @param int $owner_guid
 * @return bool false = exceed quota
 */
function tp_upload_check_quota($image_size, $owner_guid) {
	static $quota;
	
	if (!isset($quota)) {
		$quota = elgg_get_plugin_setting('quota', 'tidypics');
		$quota = 1024 * 1024 * $quota;
	}

	if ($quota == 0) {
		// no quota
		return true;
	}

	$owner = get_entity($owner_guid);
	$image_repo_size_md = (int)$owner->image_repo_size;
	
	return ($image_repo_size + $image_size) < $quota;
}