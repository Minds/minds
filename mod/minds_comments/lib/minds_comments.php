<?php

function minds_comments_view_list($type, $pid) {
	$limit = 3;

	$mc = new MindsComments();
	$call = $mc -> output($type, $pid, 3, 0);
	if($call['error']){
		return false;
	}
	$count = $call['hits']['total'];
	$comments = array_reverse($call['hits']['hits']);

	foreach ($comments as $comment) {
		$visible .= minds_comments_view_comment($comment);
	}

	if ($count > 0 && $count > $limit) {
		$remainder = $count - $limit;
		if ($limit > 0) {
			$summary = elgg_echo('hj:alive:comments:remainder', array($remainder));
		} else {
			$summary = elgg_echo('hj:alive:comments:viewall', array($remainder));
		}
	}

	return elgg_view('minds_comments/list', array('summary' => $summary, 'visible' => $visible, 'hidden' => $hidden));
}

function minds_comments_view_comment($comment) {
	$owner = get_entity($comment['_source']['owner_guid'], 'user');
	if(!$owner){
		return false;
	}
	$icon = elgg_view_entity_icon($owner, 'tiny');

	$author = elgg_view('output/url', array('text' => $owner -> name, 'href' => $owner -> getURL(), 'class' => 'minds-comments-owner'));
	
	$menu = elgg_view_menu('comments', array(
		    'type' => $comment['_type'],
		    'pid'=>$comment['_source']['pid'],
			'id'=>$comment['_id'],
			'thumbs:up'=>$comment['_source']['thumbs']['up'],
			'thumbs:down'=>$comment['_source']['thumbs']['down'],
			'owner_guid'=>$comment['_source']['owner_guid'],
		    'handler' => $handler,
		    'class' => 'elgg-menu-hz',
		    'sort_by' => 'priority',
		    'params' => $params
		));
	
	$content .= $menu;
	
	$content .= $author . ': ' . minds_filter($comment['_source']['description']);
	$content .= '<br/><span class="minds-comments-timestamp"' . elgg_view_friendly_time($comment['_source']['time_created']) . '</span>';
	
	return elgg_view_image_block($icon, $content, array('class' => 'minds-comment'));

}

/**
 * An asyncrous function to notify users in the comment thread
 */
function minds_comments_notification($type, $pid, $description){
	$mc = new MindsComments();
	$call = $mc -> output($type, $pid, 50, 0);
	$count = $call['hits']['total'];
	$comments = $call['hits']['hits'];
	
	foreach($comments as $comment){
	
		$to_guids[] = $comment['_source']['owner_guid'];
	
	}
	if($type=='entity'){
		$entity = get_entity($pid, 'object');
		$owner_guid = $entity->getOwnerGUID();
	}elseif($type=='river'){
		$post = elgg_get_river(array('ids'=>array($pid)));
		$owner_guid = $post[0]->subject_guid;
	}else{
		return false;
	}
	$to_guids[] = $owner_guid;
	$to = array_unique($to_guids);
	
	notification_create($to, elgg_get_logged_in_user_guid(), $pid, array('type'=>$type,'description'=>$description, 'notification_view'=>'comment'));
}

function minds_comment_count($type, $pid){
	$mc = new MindsComments();
	$call = $mc -> output($type, $pid, 0, 0);
	$count = $call['hits']['total'];
	
	return $count;
}

/**
 * Convert any old comments over to the new system
 */
function minds_comments_migrate(){
	$comments = elgg_get_entities(array('types'=>array('object'), 'subtypes'=>array('hjannotation'), 'limit'=>100000));
	foreach($comments as $comment){
		if($comment->parent_guid != 0){
			$type = 'entity';
			$pid = $comment->parent_guid;
		}else{
			$type = 'river';
			$pid = $comment->river_id;
		}
		$mc = new MindsComments();
		$create = $mc->create($type, $pid, $comment->annotation_value, $comment->owner_guid);
	}
}
function hj_alive_count_comments($entity, $params) {
	/*$parent_guid = elgg_extract('parent_guid', $params, null);
	$river_id = elgg_extract('river_id', $params, null);
	$annotation_name = elgg_extract('aname', $params, 'generic_comment');

	$options = array('type' => 'object', 'subtype' => 'hjannotation', 'owner_guid' => null,
	//'container_guid' => $container_guid,
	'metadata_name_value_pairs' => array( array('name' => 'annotation_name', 'value' => $annotation_name), array('name' => 'annotation_value', 'value' => '', 'operand' => '!='), array('name' => 'parent_guid', 'value' => $parent_guid), array('name' => 'river_id', 'value' => $river_id)), 'count' => true, 'limit' => 3, 'order_by' => 'e.time_created desc');

	$count = elgg_get_entities_from_metadata($options);*/
	$count = 0;
	return $count;
}
