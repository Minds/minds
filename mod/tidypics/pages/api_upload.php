<?php
/**
 * Upload photos/images from the API
 * 
 * @author Mark Harding (mark@minds.com)
 *
 */
oauth2_pam_handler();
gatekeeper(); 
elgg_load_library('tidypics:upload');
$img_river_view = elgg_get_plugin_setting('img_river_view', 'tidypics');

$title = get_input('title', $_FILES['file']['name']);
$description = get_input('description');
$license = get_input('license');
$tags = get_input('tags');
$access = get_input('access', get_default_access());

//find the users mobile upload album
$albums = elgg_get_entities_from_metadata(array(
													'type'=> 'object',
													'subtype' => 'album',
													'owner_guid' => elgg_get_logged_in_user_guid(),
													'metadata_name_value_pairs' => array('name'=>'mobile', 'value'=>true)
													
										));

$album = $albums[0];
//if the album cant be found then lets create one
if (!$album) {
	$album = new TidypicsAlbum();
	$album->owner_guid = elgg_get_logged_in_user_guid();
	$album->title = 'Mobile Uploads';
	$album->access_id = $access;
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

// create the image object for each upload

	$mime = $_FILES['file']['type'];
	$image = new TidypicsImage();
	$image->title = urldecode($title);
	$image->description = urldecode($description);
	$image->tags = urldecode($tags);
	$image->license = urldecode($license);
	$image->container_guid = $album->getGUID();
	$image->setMimeType($mime);
	$image->access_id = $album->access_id;

	$result = $image->save($_FILES['file']);
	//error_log('Save: ' . $image->getGUID());
	if ($result) {
	//array_push($uploaded_images, $image->getGUID());
	$album->prependImageList(array($image->getGUID()));
			add_to_river('river/object/image/create', 'create', $image->getOwnerGUID(), $image->getGUID());
	}
