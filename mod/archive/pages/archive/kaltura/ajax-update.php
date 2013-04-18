<?php

echo "no errors at line 3";

elgg_load_library('archive:kaltura');

$access_id = get_input('access_id');
$update_plays = get_input('update_plays');
$delete_entry_id = get_input('delete_entry_id');
$video_id = get_input('id');
$collaborative = get_input('collaborative');
$uploaded_entry_id = get_input('uploaded_entry_id');
$update_entry_id = get_input('update_entry_id');

if(empty($access_id) && empty($update_plays) && empty($delete_entry_id) && empty($collaborative) && empty($uploaded_entry_id)) die;

/**
 * Update play counts
 * @todo make this update via a cron job instead
 */
if($update_plays) {
	//update plays status
	try {
		$ob = kaltura_get_entity($update_plays);
		//open the kaltura list with admin privileges
		$kmodel = KalturaModel::getInstance();
		$entry = $kmodel->getEntry($update_plays);
		$metadata = kaltura_get_metadata($ob);
	}
	catch(Exception $e) {
		$error = $e->getMessage();
		if($e->getCode() == 'ENTRY_ID_NOT_FOUND' && $metadata->kaltura_video_id) {
			//Delete the elgg object if the video not exists in kaltura
			$ob->delete();
			echo elgg_echo('kalturavideo:error:objectnotavailable');
			exit;
		}
		die; //silent dying?
	}


	if ( $entry && $ob) {
		//show the number of plays
		echo $entry->plays;

		//if something goes wrong from now, we can obviate it

		//get the metadata for plays only
		$metadata = kaltura_get_metadata($ob);

		if($ob->kaltura_video_plays != $entry->plays) {
			$value = add_metastring($entry->plays);
			$name = add_metastring('kaltura_video_plays');

			//update manually the play number to avoid user access restrictions
			if($value && $name) {
				//check the cache and delete the entry
				static $metabyname_memcache;
				if ((!$metabyname_memcache) && (is_memcache_available()))
					$metabyname_memcache = new ElggMemcache('metabyname_memcache');
				if ($metabyname_memcache) $metabyname_memcache->delete($ob->getGUID().":$name");

				//check if this metadata exists:
				if(get_data_row("SELECT * FROM {$CONFIG->dbprefix}metadata WHERE entity_guid=".$ob->getGUID()." AND name_id='$name'")) {
					//updating existing metadata
					$result = update_data("UPDATE {$CONFIG->dbprefix}metadata set value_id=$value where entity_guid=".$ob->getGUID()." and name_id=$name");
				}
				else {
					//creating the new metadata
					$result = insert_data("INSERT INTO {$CONFIG->dbprefix}metadata (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES (".$ob->getGUID().",$name,$value,'integer',".$ob->getOwnerGUID().",".$ob->access_id.",".time().",'yes')");
				}
			}
		}

	}
	exit;
}

echo "no errors line 79";
gatekeeper();

if(!empty($uploaded_entry_id)) {

	try {
		$kmodel = KalturaModel::getInstance();
		$uploaded_entry = $kmodel->getEntry ( $uploaded_entry_id );

		$ob = kaltura_update_object($uploaded_entry,null,$access,elgg_get_logged_in_user_guid(),null,true);
	
		if($ob) $ob->save();
		//var_dump($ob);

	} catch(Exception $e) {
		
		$code = $e->getCode();
		$error = $e->getMessage();
		
	}
	echo "no errors line 98";
	if( $code == 'ENTRY_ID_NOT_FOUND') {
		//we can delete the elgg object
		$ob = kaltura_get_entity($delete_entry_id);
		if($ob) $ob->delete();
		echo str_replace("%ID%",$delete_entry_id,elgg_echo("kalturavideo:action:deleteok"));
	}

	if($error) {
		echo $error;
	}
echo "no errors line 109";
	exit;
}

//change privacity status
$ob = kaltura_get_entity($video_id);
$metadata = kaltura_get_metadata($ob);

if($ob->canEdit() && $metadata->kaltura_video_id==$video_id && in_array($collaborative,array('yes','no'))) {
	$entry = array('id' => $video_id);
	//Privacity Status
	$ob->kaltura_video_collaborative = ($collaborative=='yes');
	if($ob->save())	system_message(str_replace("%1%",$video_id,elgg_echo("kalturavideo:text:collaborativechanged")));
	else register_error(str_replace("%1%",$video_id,elgg_echo("kalturavideo:text:collaborativenotchanged")));
	exit;
}

//only this status allowed
$acc_arr = get_write_access_array();
//array('me','friends','thisgroup','loggedin','public')
if(!array_key_exists($access_id,$acc_arr)) {
	register_error(str_replace("%1%",$video_id,elgg_echo("kalturavideo:text:statusnotchanged")));
	exit;
}


if($ob->canEdit() && $metadata->kaltura_video_id==$video_id) {
	//changes not allowed if not owner (or administrator)
	$ob->access_id = $access_id;
	if($ob->save()) {
		system_message(str_replace("%2%",$video_id,str_replace("%1%",$acc_arr[$access_id],elgg_echo("kalturavideo:text:statuschanged"))));
	}
	else {
		register_error(str_replace("%1%",$video_id,elgg_echo("kalturavideo:text:statusnotchanged")));
	}
	exit;

}
else {
	register_error(str_replace("%1%",$video_id,elgg_echo("kalturavideo:text:statusnotchanged")));
	exit;
}


?>
