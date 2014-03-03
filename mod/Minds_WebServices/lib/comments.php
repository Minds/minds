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

	$mc = new MindsComments();
	$call = $mc -> output($type, $pid, $limit, $offset);
	$count = $call['hits']['total'];
	$comments = array_reverse($call['hits']['hits']);

	foreach ($comments as $comment) {
		$single['id'] = $comment['_id'];
		
		$owner = get_entity($comment['_source']['owner_guid']);
		$single['owner']['name'] = $owner->name;
		$single['owner']['username'] = $owner->username;
		$single['owner']['guid'] = $owner->guid;
		$single['owner']['avatar_url'] = $owner->getIconUrl('small');

		$single['description'] = $comment['_source']['description'];
		$single['time_created'] = $comment['_source']['time_created'];
		$return[] = $single;
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
				"minds_comments_ws_get",
				array(
						'type' => array ('type' => 'string', 'required' => false, 'default'=>'any'),
						'pid' => array ('type' => 'string', 'required' => true),
						'limit' => array ('type' => 'int', 'required'=> false, 'default'=>10),
						'offset' =>  array ('type' => 'int', 'required'=> false, 'default'=>0),
					),
				"Get a list of comments",
				'GET',
				true,
				true);
				
function minds_comments_ws_getbyid($id, $type) {
    
	$mc = new MindsComments();
        
        return $comment = $mc->single($type, $id);
}

expose_function('comments.get.byid',
				"minds_comments_ws_getbyid",
				array(
						'id' => array ('type' => 'string', 'required' => true),
                                                'type' => array ('type' => 'string', 'required' => false, 'default'=>'entity'),
					),
				"Get a comment by it's id",
				'GET',
				true,
				true);

/**
 * Posts a comment
 */
function minds_comments_ws_post($comment,$pid, $type){
		
	$mc = new MindsComments();
	$create = $mc->create($type, $pid, $comment);
	
	if($create['ok'] == true){
		system_message(elgg_echo('minds_comments:save:success'));
	
		minds_comments_notification($type, $pid, $comment);
		/**
		 * OUTPUT
		 */
		$single['id'] = time().elgg_get_logged_in_user_guid();
		
		$owner = elgg_get_logged_in_user_entity();
		$single['owner']['name'] = $owner->name;
		$single['owner']['username'] = $owner->username;
		$single['owner']['guid'] = $owner->guid;
		$single['owner']['avatar_url'] = $owner->getIconUrl('small');

		$single['description'] = $comment;
		$single['time_created'] = time();
		return $single;
	} else {
		 return 'minds_comments:save:error';
	}

}

expose_function('comments.post',
				"minds_comments_ws_post",
				array(
						'comment' => array ('type' => 'string'),
						'pid' => array ('type' => 'string', 'required' => true),
						'type' => array('type'=>'string', 'required'=>false, 'default'=>'entity'),
					),
				"Make a comment",
				'POST',
				true,
				true);
