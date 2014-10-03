<?php

global $DOMAIN;
$DOMAIN='www.word.am';

require(dirname(dirname(__FILE__)) . '/engine/start.php');

use minds\entities;

elgg_set_ignore_access(true);

$newsfeed = new minds\core\data\call('newsfeed');
$timeline = new minds\core\data\call('timeline');
$indexes = new minds\core\data\call('entities_by_time');

/*$groups = $indexes->getRow('group', array('limit'=>100000));

foreach($groups as $guid => $ts){

	$columns = $timeline->getRow($guid, array('limit'=>100000));
	foreach($columns as $g => $t){
		$array = $newsfeed->getRow($g);
		migrate(array($array));
		$columns[$g] = $g;
	}

	$indexes->removeRow("activity:container:$guid");
	$indexes->insert("activity:container:$guid", $columns);
	//echo "$guid \n";
}*/

$user = new entities\user('garrett.burns');
$videos = minds\core\entities::get(array('subtype'=>'video', 'owner_guid'=>$user->guid, 'limit'=>1000));

foreach($videos as $video){

	$activity = new entities\activity();
	$activity->owner_guid = $video->owner_guid;
	$activity->time_created = $video->time_created;
	$activity->article_media = 'video';
	$activity->setTitle($video->title)
		->setBlurb($video->description)
		->setURL($video->getURL())
		->setThumbnail($video->getIconURL())
		->save();

}
exit;
/**
 * Convert all of our newsfeed object into new style entities
 */
function migrate($rows){
//foreach($newsfeed->get('', 1000000) as $guid => $row){
	foreach($rows as $row){	
		$activity = new entities\activity($row['id']);
	//	if($activity->time_created == time())
	//		continue;

		$activity->guid = $row['id'];
		$activity->time_created = $row['posted'];
		
		$object = isset($row['object']) ? unserialize($row['object']) : json_decode($row['objectObj']);
		$subject = isset($row['subject']) ? unserialize($row['subject']) : json_decode($row['subjectObj']);
		if(!$subject->guid){
			echo "Not a user \n";
			continue;
		}
		$activity->owner_guid = $subject->guid;
		
		if($row['action_type'] != 'create'){
			echo "Not a create \n";
			continue; //remind don't matter..
		}
			
		if(isset($row['batch_guids']))
			$object->subtype = 'batch';
		

		switch($object->subtype){
			case 'batch':
				$batch_guids = json_decode($row['batch_guids'], true);
				$images = array();
				foreach($batch_guids as $guid){
					$images[] = array(
						'src' => elgg_get_site_url() . 'archive/thumbnail/'.$guid .'/large',
						'href' =>  elgg_get_site_url() . 'archive/view/'.$object->guid.'/'.$guid,
					);
				}
				$activity->setCustom('batch', $images);
				break;
			case 'blog':
				
				$activity->setTitle($object->title)
						->setBlurb($object->excerpt)
						->setURL($object->getURL())
						->setThumbnail(minds_fetch_image($object->description,$object->owner_guid));
				
				break;
			case 'post':
			case 'wallpost':
				//do we have an attachment
				if(isset($row['attachment_guid'])){
					$attachment = new \PostAttachment($row['attachment_guid']);
					if($attachment->subtype == 'image'){
						$activity->setCustom('batch', array(array(
							'src' => $attachment->getIconURL('large'),
							'href' => $attachment->getURL()
						)));
					} else {
						$activity->setTitle($attachment->originalfilename.'123')
								->setBlurb(round($attachment->size / (1024 * 1024)).' MB')
								->setURL($attachment->getIconURL('medium'));
					}
				}
		
				$activity->setMessage($row['body']);
				
				break;
			default:
				echo "what about $object->subtype \n";
		}
		
	//	$activity->indexes = array('awaiting-indexing');
		$guid = $activity->save();
		echo "successfully migrated $guid to an entity \n";
	}
}
exit;
/*$feeds = array(
	'mark',
	'markandrewculp',
	'drdro'
);

foreach($feeds as $username){
	echo "doing $username \n";
	$user = new \minds\entities\user($username);
	

	//personal
	$personal = $timeline->getRow("personal:$user->guid", array('limit'=>10000));
	$indexes->removeRow("activity:user:$user->guid");
//	$indexes->insert("activity:user:$user->guid", $personal);

 	migrate($newsfeed->getRows(array_keys($personal)));

	//network 
//	$network = $timeline->getRow("$user->guid", array('limit'=>10000));
//	$indexes->removeRow("activity:network:$user->guid");
//	$indexes->insert("activity:network:$user->guid", $network);

//	migrate($newsfeed->getRows(array_keys($network)));	

}
exit;*/
/**
 * We now need to index, plus we should also adjust the access_id's at this time
 */
foreach($timeline->get('', 1000000) as $row => $data){
	$columns = array();
	foreach($data as $guid => $ts){
		$columns[$guid] = $guid;
	}
	
	if($row == 'awaiting-indexing')
		continue;
	
	/*//is personal:guid
	if($guid = end(explode(':', $row))){
		$row = $guid;
		$personal = true;
	} else {
		$personal = false;
	}*/	

	if(!is_numeric($row))
		continue; 
	
	$entity = get_entity($row);
/*	if($entity instanceof entities\user){
		echo "Moving over $entity->username 's feed \n";
		//$indexes->removeRow("activity:user:$entity->guid"); //remove any test data..
		if($personal)
			$indexes->insert("activity:user:$entity->guid", $columns);
		else
			$indexes->insert("activity:network:$entity->guid", $columns);

	}*/
	if($entity instanceof ElggGroup){
		echo "Moving over groups feed \n";
		$indexes->removeRow("activity:container:$entity->guid"); //remove any test data..
		$indexes->insert("activity:container:$entity->guid", $columns);
	}
}
