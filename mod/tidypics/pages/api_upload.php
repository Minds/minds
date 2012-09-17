<?php
/**
 * Upload photos/images from the API
 * 
 * @author Mark Harding (mark@minds.com)
 */
 
elgg_load_library('tidypics:upload');
$img_river_view = elgg_get_plugin_setting('img_river_view', 'tidypics');

$title = get_input('title');
$description = get_input('description');
$license = get_input('license');

//find the users mobile upload album
$album = elgg_get_entities_from_metadata(array(
													'type'=> 'object',
													'subtype' => 'album',
													'owner_guid' => elgg_get_logged_in_user_guid(),
													'metadata_name_value_pairs' => array('name'=>'mobile', 'value'=>true)
													
										));
//if the album cant be found then lets create one
if (!$album) {
	$album = new TidypicsAlbum();
	$album->owner_guid = elgg_get_logged_in_user_guid();
	$album->title = 'Mobile Uploads';
	$album->mobile = true;
	
	if (!$album->save()) {
		register_error(elgg_echo("album:error"));
		forward(REFERER);
	}
}
// post limit exceeded
if (count($_FILES) == 0) {
	trigger_error('Tidypics warning: user exceeded post limit on image upload', E_USER_WARNING);
	register_error(elgg_echo('tidypics:exceedpostlimit'));
	forward(REFERER);
}

// test to make sure at least 1 image was selected by user
$num_images = 0;
foreach($_FILES['file']['name'] as $name) {
	if (!empty($name)) {
		$num_images++;
	}
}
if ($num_images == 0) {
	// have user try again
	register_error(elgg_echo('tidypics:noimages'));
	forward(REFERER);
}

// create the image object for each upload
$uploaded_images = array();
$not_uploaded = array();
$error_msgs = array();
foreach ($_FILES['file']['name'] as $index => $value) {
	$data = array();
	foreach ($_FILES['file'] as $key => $values) {
		$data[$key] = $values[$index];
	}

	if (empty($data['name'])) {
		continue;
	}

	$mime = tp_upload_get_mimetype($data['name']);

	$image = new TidypicsImage();
	$image->container_guid = $album->getGUID();
	$image->setMimeType($mime);
	$image->access_id = $album->access_id;

	try {
		$result = $image->save($data);
	} catch (Exception $e) {
		array_push($not_uploaded, $data['name']);
		array_push($error_msgs, $e->getMessage());
	}

	if ($result) {
		array_push($uploaded_images, $image->getGUID());

		if ($img_river_view == "all") {
			add_to_river('river/object/image/create', 'create', $image->getOwnerGUID(), $image->getGUID());
		}
	}
}