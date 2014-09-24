<?php

global $DOMAIN;
$DOMAIN='www.word.am';

require(dirname(dirname(__FILE__)) . '/engine/start.php');

use minds\entities;

elgg_set_ignore_access(true);

$newsfeed = new minds\core\data\call('newsfeed');
$timeline = new minds\core\data\call('timeline');
$indexes = new minds\core\data\call('entities_by_time');

/**
 * Convert all of our newsfeed object into new style entities
 */
foreach($newsfeed->get('', 1000000) as $guid => $row){
	$activity = new entities\activity();
	$activity->guid = $row['id'];
	$activity->time_created = $row['posted'];
	
	$object = json_decode($row['objectObj']);
	$subject = json_decode($row['subjectObj']);
	if(!$subject->guid)
		continue;
	$activity->owner_guid = $subject->guid;
	
	if($row['action_type'] != 'create')
		continue; //remind don't matter..
		
		
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

	}
	
	$activity->indexes = array('awaiting-indexing');
	$guid = $activity->save();
	echo "successfully migrated $guid to an entity \n";
}

/**
 * We now need to index, plus we should also adjust the access_id's at this time
 */
foreach($timeline->get('', 100000) as $row => $data){
	$columns = array();
	foreach($data as $guid => $ts){
		$columns[$guid] = $guid;
	}
	
	if($row == 'awaiting-indexing')
		continue;
	
	if(!is_numeric($row))
		continue; 
	
	$entity = get_entity($row);
	if($entity instanceof entities\user){
		echo "Moving over users feed \n";
		$indexes->removeRow("activity:user:$entity->guid"); //remove any test data..
		$indexes->insert("activity:user:$entity->guid", $columns);
	}
	if($entity instanceof ElggGroup){
		echo "Moving over groups feed \n";
		$indexes->removeRow("activity:container:$entity->guid"); //remove any test data..
		$indexes->insert("activity:container:$entity->guid", $columns);
	}
}
