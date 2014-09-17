<?php
$message = get_input('message');
$accounts = get_input('accounts');
$schedule_date = get_input('schedule_date');
$schedule_hour = get_input('schedule_time_hour');
$schedule_minute = get_input('schedule_time_minute');
$attachment = $_FILES['attachment'];

$post = new ElggDeckPost();
$post->message = $message;
$post->attachment = false;
$post->to_guid = get_input('to_guid');
$post->container_guid = get_input('container_guid');
$post->access_id = get_input('access_id');

$post->meta_title = get_input('preview-title');
$post->meta_description = get_input('preview-description');
$post->meta_icon = get_input('preview-icon');
$post->meta_url = get_input('preview-url');

if(isset($attachment['name']) && !empty($attachment['name'])){
	

	//@todo Make these actual entities.  See exts #348.
	$file = new PostAttachment();
	/*$file->owner_guid = elgg_get_logged_in_user_guid();
	$file->setFilename("attachments/{$guid}/{$name}.jpg");
	$file->open('write');
	$file->write($resized);
	$file->close();*/
	$guid = $file->save($attachment);
	//$files[] = $file;
	$post->attachment = $guid;
}
	
//do we have sub accounts?
foreach($accounts as $k=>$account){
	$parts = explode('/',$account);
	if(count($parts) > 1){
		$sub_accounts[] = $account;
		unset($accounts[$k]);
	}
}
$post->setAccounts($accounts);
$post->setSubAccounts($sub_accounts);

//CURRENT TIME
$time = time();
$scheduled = strtotime($schedule_date . " $schedule_hour:$schedule_minute");

//if scheduled over 5 mins into the future, schedule
if($scheduled > ($time+300)){
	$post->schedulePost($scheduled);
	system_message('Scheduled');
} else {
	$post->doPost();
	$wallpost = elgg_get_entities(array('subtype'=>'wallpost', 'owner_guid' =>elgg_get_logged_in_user_guid(), 'limit'=>1));
	$wallpost= $wallpost[0]; 
	echo elgg_view_river_item(new ElggRiverItem(array(
				'subject_guid' => $wallpost->owner_guid,
				'body' => $wallpost->message,
				'view' => 'river/object/wall/create',
				'object_guid' => $wallpost->guid, //needed until we do some changes to the thumbs and comments plugins
				'attachment_guid' => $wallpost->attachment,
				'access_id' => $wallpost->access_id,
		)));
	system_message('Message posted');
}


