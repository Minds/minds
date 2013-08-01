<?php
/**
 * Created by Roy Cohen
 * User: root
 * Date: 7/17/13
 * Time: 12:43 PM
 * AddAngular create new entity in elgg and uploading files to Elgg excluding Video and Audio files which are sent to Kaltura Server.
 * AddAngular also updates the elgg entities.
 */
elgg_load_library('archive:kaltura');
elgg_load_library('tidypics:upload');

// Get variables
$title = get_input("title");
$desc = get_input("description");
$access_id = (int) get_input("accessId");
$license = get_input("license");
$tags = get_input("tags");
$mime_type = get_input("fileType");
$entryId = get_input("entryId");
//If the entity doesn't exsits then entityId will be null and will be created later.
$guid = get_input("guid", null);
$entryId = get_input("entryId", null);

$thumbSec = get_input("thumbSecond", 0);
$entity = get_entity($guid);

$container_guid = elgg_get_logged_in_user_guid();
$user_guid = elgg_get_logged_in_user_guid();

elgg_make_sticky_form('generic-upload');

//if ($guid) //TODO: add check for licence in UI
//{
//    if($license == 'not-selected')
//    {
//        register_error(elgg_echo('minds:license:not-selected'));
//        echo "not-selected";
//        exit;
//    }
//}

//Setting Kaltura object
$mediaEntry = new KalturaMediaEntry();
$mediaEntry->name = strip_tags($title);
$mediaEntry->description = $desc;
//$mediaEntry->tags = $tags;

if($guid && $entryId) //Only if elgg entity exists and we have an entryId
    $mediaEntry->id = $entryId;

if ($mime_type != null)
    $mediaEntry->mediaType = $mime_type;

// If Video/Audio then upload to Kaltura and create entity in Elgg
if(file_get_simple_type($mime_type) == 'video' || file_get_simple_type($mime_type) == 'audio' || $mediaEntry->mediaType == 'video')
{
    $ob = kaltura_update_object($mediaEntry, null, $access_id, $user_guid, $container_guid, true,
        array('title' => $title,
              'uploaded_id' => $mediaEntry->id,
              'license' => $license,
              'thumbnail_sec' => $thumbSec));
    if($ob)
    {
        elgg_clear_sticky_form('file');
        system_message(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedok")));
    }
    else
    {
        register_error(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedko"))."\n$error");
    }
}// If Image then create an album. Don't upload to Kaltura.
elseif (file_get_simple_type($mime_type) == 'image' || $mediaEntry->mediaType == 'image'){
    //find the users uploads album
    $albums = elgg_get_entities_from_metadata(array(
        'type'=> 'object',
        'subtype' => 'album',
        'owner_guid' => elgg_get_logged_in_user_guid()
//        'metadata_name_value_pairs' => array('name'=>'uploads', 'value'=>true)

    ));

    /*TODO foreach album un albums and check the guid and then use this album*/

    $album = $albums[1];
    //if the album cant be found then lets create one
    if (!$album) {
        $album = new TidypicsAlbum();
        $album->owner_guid = elgg_get_logged_in_user_guid();
        $album->title = 'Uploads';
        $album->access_id = 2;
        $album->uploads = true;


        if (!$album->save()) {
            register_error(elgg_echo("album:error"));
            forward(REFERER);
        }
    }


    if ($entityId)
    {
        $image = get_entity($entityId);
    }
    else
    {
        $image = new TidypicsImage();
    }

    $image->title = $title;
    $image->description = $desc;
    $image->container_guid = $album->getGUID();
    $image->setMimeType($mime_type);
    $image->tags = $tags;
    $image->access_id = $access_id;
    $image->license = $license;
    $image->category = $category;

    $result = $image->save($_FILES['fileData']);
    //error_log('Save: ' . $image->getGUID());
    if ($result) {
        //array_push($uploaded_images, $image->getGUID());
        $album->prependImageList(array($image->getGUID()));
        $image->guid = $image->getGUID();
        echo $image->getGUID() . " ";

        add_to_river('river/object/image/create', 'create', $image->getOwnerGUID(), $image->getGUID());
        exit;
//        forward($image->getURL());
    }
}
//anything else should be forwarded to files. Elgg File entity.
else{
    if ($entityId)
    {
        $file = get_entity($entityId);
    }
    else
    {
        $file = new ElggFile();
    }

    $file->subtype = "file";

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