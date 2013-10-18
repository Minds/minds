<?php
/**
 * JSON river item view
 *
 * @uses $vars['item']
 */

global $jsonexport;


if (!isset($jsonexport['activity'])) {
	$jsonexport['activity'] = array();
}

$item = $vars['item'];
$annotation = $vars['item']->getAnnotation();
$object = $item->getObjectEntity();
$subject = $item->getSubjectEntity();

if (elgg_view_exists($item->view, 'default')) {
	$item->string = elgg_view('river/elements/summary', array('item' => $item), FALSE, FALSE, 'default');
}

if($object->type == "user" || $object->type == "group"){
		$item->object_metadata['name'] = $object->name;
		$item->object_metadata['username'] = $object->username;
		$item->object_metadata['avatar_url'] = $object->getIconUrl('medium');
		if($annotation){
		$item->object_metadata['message'] = $annotation->value;
		} 
		$item->object_metadata['description'] = $object->description;
	} else {
		$item->object_metadata['name'] = $object->title;
		if($annotation){
		$item->object_metadata['message'] = $annotation->value;
		} 
		$item->object_metadata['description'] = $object->description;
		
		//small hack for wall
		if(get_subtype_from_id($object->subtype) == 'wallpost'){
			//@todo make this blend in with the standard functions
			$item->object_metadata['to_username'] = get_entity($object->to_guid,'user')->username;
			$item->object_metadata['to_name'] = get_entity($object->to_guid,'user')->name;
			$item->object_metadata['message'] = strip_tags($object->message);
		}
		//small hack for bookmarks
		if(get_subtype_from_id($object->subtype) == 'bookmarks'){
			$item->object_metadata['address'] = $object->address;
		}
		//small hack for studio videos
		if($object->getSubtype() == 'kaltura_video'){
			$item->object_metadata['thumbnail'] = $object->kaltura_video_thumbnail;
			$item->object_metadata['video_id'] = $object->kaltura_video_id;
		}
		if($object->getSubtype() == 'blog'){
			$item->object_metadata['thumbnail'] = minds_fetch_image($object->description, $object->owner_guid);
			$item->object_metadata['excerpt'] = $object->excerpt ?  $object->excerpt :  substr(strip_tags($object->description), 0, 140);
			$item->object_metadata['description'] = str_replace('<a', '<a id="minds-link"', $object->description); 
		}
		//small hack for images
		if($object->getSubtype()=='image' || $object->getSubtype()=='album'){
			$item->object_metadata['thumbnail'] = $object->getIconURL('large');
		}
		//small hack for tidypics_batch
		if($object->getSubtype()=='tidypics_batch'){
			// Get images related to this batch
			$images = elgg_get_entities_from_relationship(array(
				'relationship' => 'belongs_to_batch',
				'relationship_guid' => $object->getGUID(),
				'inverse_relationship' => true,
				'type' => 'object',
				'subtype' => 'image',
				'offset' => 0,
			));
			$album = $object->getContainerEntity();
			foreach($images as $image){
				$single['guid'] = $image->guid;
				$single['title'] = $image->getTitle();
				$single['thumbnail'] = $image->getIconURL('small');
				$icons[] = $single;
			}
			$item->object_guid = $album->guid;
			$item->object_metadata['guid'] = $album->guid;
			$item->object_metadata['title'] = $album->getTitle();
			$item->object_metadata['images'] = $icons;
		}
		//small hack for albums
		if($object->getSubtype()=='album'){
			$images = $object->getImageList();
			$album = $object;
			foreach($images as $image_guid){
				$image = get_entity($image_guid);
				$single['guid'] = $image->guid;
                                $single['title'] = $image->getTitle();
                                $single['thumbnail'] = $image->getIconURL('large');
                                $icons[] = $single;
			}
			$item->object_guid = $album->guid;
                        $item->object_metadata['guid'] = $album->guid;
                        $item->object_metadata['title'] = $album->getTitle();
                        $item->object_metadata['images'] = $icons;
		}	
}

if($subject->type == "user" || $object->type == "group"){
		$item->subject_metadata['name'] = $subject->name;
		$item->subject_metadata['username'] = $subject->username;
		$item->subject_metadata['avatar_url'] = $subject->getIconUrl('small');
	} else {
		$item->subject_metadata['name'] = $subject->title;
}
//@todo make this 
if ($item->action_type != 'create' && $item) {
    $river_id = $item->id;
    $pid = $river_id;
} else {
    $guid = $object->guid;
    $pid = $guid;
}
$params['parent_guid'] = $guid;
$params['river_id'] = $river_id;

$item->comments['count'] = minds_comment_count(null, $pid);

$jsonexport['activity'][] = $vars['item'];
