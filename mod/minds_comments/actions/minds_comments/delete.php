<?php
$id = get_input('id');
$type = get_input('type');
if(!$id){
	return false;
}

$mc = new MindsComments();
$comment =$mc->single($type,$id);

/*
 * @todo allow users who own the parent to delete comments
 */
if($comment['_source']['owner_guid']==elgg_get_logged_in_user_guid()|| elgg_is_admin_logged_in()){
	$mc->delete($type,$id);
}

/*
 * Purge the comments cache
 */
$es = new elasticsearch();
$es->purgeCache('comments.'.$type.'.'.$comment['_source']['pid']);

