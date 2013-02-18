<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
/**
 * Retrives a list of comments
 */ 
function minds_comments_ws_get($type, $pid, $limit, $offset){
		
	if($type=='any'){
		$type = null;
	}
	$limit = 3;

	$mc = new MindsComments();
	$call = $mc -> output($type, $pid, $limit, $offset);
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

	
	return $return;
	
} 
				
expose_function('comments.get',
				"comments_get",
				array(
						'guid' => array ('type' => 'int', 'required' => false),
						'river_id' => array ('type' => 'int', 'required' => false),
					),
				"Get a list of comments",
				'GET',
				true,
				true);
				
/**
 * Posts a comment
 */
function comments_post($comment,$guid, $river_id){
	if(!$guid && !$river_id){
		return false;
	}	
 	$annotation = new hjAnnotation();
	$annotation->annotation_value = $comment;
	$annotation->annotation_name = 'generic_comment';
	$annotation->title = '';
	$annotation->owner_guid = elgg_get_logged_in_user_guid();
	//set it as metadata and then there is no problem
	$annotation->parent_guid = $guid;
	$annotation->river_id = $river_id;
	$annotation->access_id = ACCESS_DEFAULT;
	$guid = $annotation->save();
	
	if($guid){
		return true;
	} else {
		return false;
	}
}

expose_function('comments.post',
				"comments_post",
				array(
						'comment' => array ('type' => 'string'),
						'guid' => array ('type' => 'int', 'required' => false),
						'river_id' => array ('type' => 'int', 'required' => false),
					),
				"Make a comment",
				'POST',
				true,
				true);