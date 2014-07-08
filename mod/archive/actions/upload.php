<?php
/**
 * Created by Roy Cohen
 * User: root
 * Date: 7/17/13
 * Time: 12:43 PM
 * AddAngular create new entity in elgg and uploading files to Elgg excluding Video and Audio files which are sent to Kaltura Server.
 * AddAngular also updates the elgg entities.
 * 
 * THIS FILE IS A MESS!! CLEAN IT UP..
 */
 
elgg_load_library('tidypics:upload');

// Get variables
$title = get_input("title");
$desc = get_input("description");
$access_id = (int) get_input("access_id", 2);
$license = get_input("license");
$tags = get_input("tags");
$mime_type = get_input("fileType", tp_upload_get_mimetype($_FILES['fileData']['name']));
$entryId = get_input("entryId");
//If the entity doesn't exsits then entityId will be null and will be created later.
$guid = get_input("guid");
$entryId = get_input("entryId");
$album_guid = get_input('albumId');

$thumbSec = get_input("thumbSecond", 0);
$entity = get_entity($guid, 'object');

$container_guid = elgg_get_logged_in_user_guid();
$user_guid = elgg_get_logged_in_user_guid();

switch($mime_type){
	case "video":
	case "audio":
		$entity = new minds\plugin\archive\entities\video();
		
		$entity->title = $title;
		$entity->description = $desc;
		$entity->owner_guid = elgg_get_logged_in_user_guid();
		$entity->license = $license;
		$entity->upload($_FILES['fileData']['tmp_name']);
	
		if($guid = $entity->save()){
			
	    	echo strval($guid);
			//	system_message(elgg_echo('archive:upload:success'));
			exit;
			
		} else {
			system_message(elgg_echo('archive:upload:failed'));
		}
	
		break;
		
	case "image":
		
		break;
	default:
		
	
}

if (file_get_simple_type($mime_type) == 'image' || $mediaEntry->mediaType == 'image'){
	
	// If Image then create an album. Don't upload to Kaltura.
 	if ($guid){
       	$image = new TidypicsImage($guid);
        $new_image = false;
	} else {
        $image = new TidypicsImage();
        $new_image = true;
    }

	if($album_guid){
		$album = new TidypicsAlbum($album_guid);
	} else {
		if($image->getContainerEntity() instanceof TidypicsAlbum)
			$album = $image->getContainerEntity();
	}

	if(!$album){
		$albums = elgg_get_entities(array( 	'type'=> 'object',
							'subtypes' => array('album'),
							'owner_guid' => elgg_get_logged_in_user_guid(),
							));
		$album = $albums[0];
	} 

    $image->title = $title;
    $image->description = $desc;
	$image->super_sybtype = 'archive';
    $image->container_guid = $album->getGUID();
    //$image->setMimeType($mime_type);
    $image->tags = $tags;
    $image->access_id = $access_id;
    $image->license = $license;
//    $image->category = $category; //No category

    $guid = $image->save($_FILES['fileData']);
  
    if ($guid) {
	    echo $guid;

        // Only post to river/update album's image list if we're creating a new image entity
        if ($new_image) {
            add_to_river('river/object/image/create', 'create', $image->getOwnerGUID(), $image->getGUID());
            $album->prependImageList(array($guid));
        }
        exit;
    }
}else{
//anything else should be forwarded to files. Elgg File entity. 
   if ($guid)
    {
        $file = get_entity($guid, 'object');
    }
    else
    {
        $file = new ElggFile();
    }

    $file->subtype = "file";
	$file->super_subtype = 'archive';

    // if no title on new upload, grab filename
    if (empty($title)) {
        $title = $_FILES['fileData']['name'];
    }

    $file->title = $title;
    $file->description = $desc;
    $file->access_id = $access_id;
    $file->container_guid = $container_guid;
    $file->tags = $tags;
    $file->license = $license;

    $tags = explode(",", $tags);
    $file->tags = $tags;

    // we have a file upload, so process it
    if (isset($_FILES['fileData']['name']) && !empty($_FILES['fileData']['name'])) {

        $prefix = "file/";
        $filestorename = elgg_strtolower(time().$_FILES['fileData']['name']);


        $mime_type = $file->detectMimeType($_FILES['fileData']['tmp_name'], $_FILES['fileData']['type']);
        $file->setFilename($prefix . $filestorename);
        $file->setMimeType($mime_type);
        $file->originalfilename = $_FILES['fileData']['name'];
        $file->simpletype = file_get_simple_type($mime_type);

        //save the space so we can add it to our quota.
        $file->size = $_FILES['fileData']['size'];

        // Open the file to guarantee the directory exists
        $file->open("write");
        $file->close();

        move_uploaded_file($_FILES['fileData']['tmp_name'], $file->getFilenameOnFilestore());

        $guid = $file->save();

        // if image, we need to create thumbnails (this should be moved into a function)
        if ($guid && $file->simpletype == "image") {
            $file->icontime = time();

            $thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 60, 60, true);
            if ($thumbnail) {
                $thumb = new ElggFile();
                $thumb->setMimeType($_FILES['fileData']['type']);

                $thumb->setFilename($prefix."thumb".$filestorename);
                $thumb->open("write");
                $thumb->write($thumbnail);
                $thumb->close();

                $file->thumbnail = $prefix."thumb".$filestorename;
                unset($thumbnail);
            }

            $thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 153, 153, true);
            if ($thumbsmall) {
                $thumb->setFilename($prefix."smallthumb".$filestorename);
                $thumb->open("write");
                $thumb->write($thumbsmall);
                $thumb->close();
                $file->smallthumb = $prefix."smallthumb".$filestorename;
                unset($thumbsmall);
            }

            $thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 600, 600, false);
            if ($thumblarge) {
                $thumb->setFilename($prefix."largethumb".$filestorename);
                $thumb->open("write");
                $thumb->write($thumblarge);
                $thumb->close();
                $file->largethumb = $prefix."largethumb".$filestorename;
                unset($thumblarge);
            }
        }
    } else {
        // not saving a file but still need to save the entity to push attributes to database
        $file->save();
    }

    if ($guid) {
        $message = elgg_echo("file:saved");
        system_message($message);
        add_to_river('river/object/file/create', 'create', elgg_get_logged_in_user_guid(), $file->guid);
        echo $file->guid;
        exit;
//        forward($file->getURL());
    } else {
        // failed to save file object - nothing we can do about this
        $error = elgg_echo("file:uploadfailed");
        register_error($error);
        echo $file->guid;
        exit;
    }

    $container = get_entity($container_guid);
    if (elgg_instanceof($container, 'group')) {
        forward("archive/group/$container->guid/all");
    } else {
        forward("archive/$container->username");
    }
}

// If ob == true then the file is Video or Audio >> return guid of the Video/Audio.
if ($ob)
    echo $ob->guid;
// if file == true then the file is not Video || Audion || Image >> return guid of the file.
elseif ($file)
    echo $file->guid;
// If not all the above then Upload failed.
else
    echo "add failed";
exit;
