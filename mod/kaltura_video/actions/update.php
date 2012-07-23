<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/includes.php");

$video_id = get_input('kaltura_video_id');
$uploaded_id = get_input('kaltura_uploaded_video_id');
$title = get_input('title');
$desc = get_input('description');
$license = get_input('license');
$tags = get_input('tags');
$access = get_input('access_id');
$comments_on = get_input('comments_select','Off');
$rating_on = get_input('rating_select','Off');
$collaborative = get_input('collaborate_select','Off');
$is_simple_video = get_input('is_simple_video','0');
$fileData = get_input('fileData', '');
$simpleVideoCreatorModal = get_input('simple_video_creator_modal');

$url = '';
if($video_id) {

	$error = '';
	//check the video

	try {
		$kmodel = KalturaModel::getInstance();

		if($is_simple_video) {
		    $mediaEntry = new KalturaMediaEntry();
		    $mediaEntry->name = $title;
		    $mediaEntry->description = $description;
		    $mediaEntry->mediaType = KalturaMediaType_VIDEO;

		    $mediaEntry = $kmodel->addMediaEntry($mediaEntry, $_FILES['fileData']['tmp_name']);
		    $is_conversion_ready = false;
		    while(!$is_conversion_ready)
		    {
			$mediaEntry = $kmodel->getEntry($mediaEntry->id);
			if($mediaEntry->status == 2)
			{
			    $is_conversion_ready = true;
			}
			else
			{
			    sleep(2);
			}
		    }

		    $entry = $kmodel->appendMediaToMix($video_id, $mediaEntry->id);
		}
		elseif($uploaded_id)
		{
			/* $is_conversion_ready = false;
		    while(!$is_conversion_ready)
		    {*/
			//$mediaEntry = $kmodel->getEntry($uploaded_id);
			/*
			if($mediaEntry->status == 2)
			{
			    $is_conversion_ready = true;
			}
			else
			{
			    sleep(2);
			}
		    }*/
			$entry = $kmodel->getEntry($video_id);
		    //$entry = $kmodel->appendMediaToMix($video_id, $uploaded_id);
		} else {
			 $entry = $kmodel->getEntry($video_id);
		}

		$ob = kaltura_get_entity($video_id);

		//check if belongs to this user (or is admin)
		if(!($ob->canEdit())) {
			$error = elgg_echo('kalturavideo:edit:notallowed');
		}
		$user_ID = $entry->userId;
	}
	catch(Exception $e) {
		$error = $e->getMessage();
	}


	if(empty($error) && $entry instanceof KalturaMixEntry) {
		// Convert string of tags into a preformatted array
		$tagarray = string_to_tag_array($tags);

		$entry->name = strip_tags($title);
		$entry->description = $desc;

		if (is_array($tagarray)) {
			$entry->tags = implode(", ",$tagarray);
		}
		try {
			$kmodel = KalturaModel::getInstance();
			$mixEntry = new KalturaMixEntry();
			$mixEntry->name = $entry->name;
			$mixEntry->description = $entry->description;
			$mixEntry->tags = $entry->tags;
			$mixEntry->adminTags = KALTURA_ADMIN_TAGS;
			$entry = $kmodel->updateMixEntry($video_id,$mixEntry);
		}
		catch(Exception $e) {
			$error = $e->getMessage();
		}

		if(empty($error)) {
			//now update the object!
			$entry->comments_on = $comments_on; //whether the users wants to allow comments or not on the blog post
			$entry->rating_on = $rating_on; //whether the users wants to allow comments or not on the blog post
			if(!($ob = kaltura_update_object($entry,null,$access,null,null,true, array('license'=> $license, 'uploaded_id' => $uploaded_id)))) {
				$error = "Error update Elgg object";
			}
			else {
				$ob->kaltura_video_collaborative = ($collaborative=='on');
				$ob->save();
				$url = $ob->getURL();
			}
		}
	}
	if($error) {
		register_error(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedko"))."\n$error");
	}
	else {
	

		system_message(str_replace("%ID%",$video_id,elgg_echo("kalturavideo:action:updatedok")));
	}
}
else register_error("Fatal error, empty video id.");

if($simpleVideoCreatorModal) $url = $CONFIG->url.'mod/kaltura_video/kaltura/editor/closemodal.php';
if(empty($url)) $url = $_SERVER['HTTP_REFERER'];
//if(strpos($url,'/kaltura_video/edit.php') === false) $url = $CONFIG->url.'mod/kaltura_video/show.php';
forward($url);

?>
