<?php

// Get variables
$title = get_input("title");
$desc = get_input("description");
$access_id = (int) get_input("access_id", 2);
$license = get_input("license");
$tags = get_input("tags");
$mime_type = get_input("fileType", file_get_simple_type($_FILES['fileData']['type']));
$entryId = get_input("entryId");
//If the entity doesn't exsits then entityId will be null and will be created later.
$guid = get_input("guid");
$entryId = get_input("entryId");
$album_guid = get_input('albumId');

$thumbSec = get_input("thumbSecond", 0);
$entity = get_entity($guid, 'object');

$container_guid = elgg_get_logged_in_user_guid();
$user_guid = elgg_get_logged_in_user_guid();
$batch = new minds\plugin\archive\entities\batch(get_input('batch_guid'));

switch ($mime_type) {
    case "video":
	if ($guid)
	    $entity = new minds\plugin\archive\entities\video($guid);
	else
	    $entity = new minds\plugin\archive\entities\video();
	$entity->title = $title;
	$entity->description = $desc;
	$entity->owner_guid = elgg_get_logged_in_user_guid();
	$entity->license = $license;

	if (!$guid)
	    $entity->upload($_FILES['fileData']['tmp_name']);
	$entity->access_id = 2;

	if ($guid = $entity->save()) {

	    $batch->addToList($entity->guid);
	    echo strval($guid);
	    //	system_message(elgg_echo('archive:upload:success'));
	    $activity = new minds\entities\activity();
	    $activity->setCustom('video', array(
			'thumbnail_src' => $entity->getIconUrl(),
			'guid' => $entity->guid))
		    ->setTitle($entity->title)
		    ->setBlurb($entity->description)
		    ->setFromEntity($entity)
		    ->save();

	    exit;
	} else {
	    system_message(elgg_echo('archive:upload:failed'));
	}

	break;
    case "audio":
	if ($guid)
	    $entity = new minds\plugin\archive\entities\audio($guid);
	else
	    $entity = new minds\plugin\archive\entities\audio();
	$entity->title = $title;
	$entity->description = $desc;
	$entity->owner_guid = elgg_get_logged_in_user_guid();
	$entity->license = $license;

	if (!$guid)
	    $entity->upload($_FILES['fileData']['tmp_name']);
	$entity->access_id = 2;

	if ($guid = $entity->save()) {
	    $batch->addToList($entity->guid);
	    echo "$guid";

	    $activity = new minds\entities\activity();
	    $activity->setCustom('video', array(
			'thumbnail_src' => elgg_get_site_url() . 'mod/archive/graphics/wave.png',
			'guid' => $entity->guid))
		    ->setTitle($entity->title)
		    ->setBlurb($entity->description)
		    ->setFromEntity($entity)
		    ->save();

	    exit;
	}

	echo "failed";
	exit;

	break;
    case "image":

	$image = new minds\plugin\archive\entities\image();

	if (!$title)
	    $title = $_FILES['fileData']['name'];

	$image->title = $title;
	$image->description = $description;
	$image->container_guid = $container_guid; //the container guid is usually blank, as it is the batch who is control at the upload level
	$image->batch_guid = get_input('batch_guid');
	$image->access_id = 2;
	$image->upload($_FILES['fileData']);
	$image->createThumbnails();

	//we don't know of an album yet, this is done by an alternative batch command
	echo $image->save(get_input('force_public', false));

	//add this image to the batch
	$batch->addToList($image->guid);
	exit;
	break;
    default:
	exit;
}
