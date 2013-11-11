<?php
/**
 * Minds Comments
 * (based on HypeAlive)
 * 
 * @author Mark Harding (mark@minds.com)
 */

elgg_register_event_handler('init', 'system', 'minds_comments_init');

/**
 * Initialize hypeAlive
 */
function minds_comments_init() {
	
	$path = elgg_get_plugins_path() . 'minds_comments/';
	
	$hj_js_ajax = elgg_get_simplecache_url('js', 'hj/framework/ajax');
    elgg_register_js('hj.framework.ajax', $hj_js_ajax);
    elgg_load_js('hj.framework.ajax');

    elgg_register_plugin_hook_handler('comments', 'all', 'minds_comments_replacement');

	// Register Libraries
	elgg_register_library('minds_comments', $path . 'lib/minds_comments.php');
	elgg_load_library('minds_comments');

	// Register actions
	elgg_register_action('comment/get', $path. 'actions/minds_comments/get.php', 'public');
	elgg_register_action('comment/save', $path . 'actions/minds_comments/save.php', 'public');
	elgg_register_action('comment/delete', $path . 'actions/minds_comments/delete.php');
	
	// Register JS and CSS libraries
	$css_url = elgg_get_simplecache_url('css', 'minds_comments');
	elgg_register_css('minds_comments', $css_url);
	
	$js_generic_url = elgg_get_simplecache_url('js', 'minds_comments');
	elgg_register_js('minds_comments', $js_generic_url);
		
	elgg_register_plugin_hook_handler('register', 'menu:comments', 'minds_comments_menu');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'minds_comment_entity_menu');
	
	/**
	 * forward users if cookie set
	 */
	 $commentCOOKIE = $_COOKIE['minds_comment'];
	 if(elgg_is_logged_in() && $commentCOOKIE && $commentCOOKIE != 'done'){
	 	$data = json_decode($commentCOOKIE, true);
	 	setcookie('minds_comment', 'done', 360, '/');//cookie valid for 10 mins
		$comment = urlencode($data['comment']);
	 	forward(elgg_add_action_tokens_to_url('action/comment/save?comment='. $comment .'&pid='.$data['pid'] .'&type='.$data['type'].'&redirect_url='.urlencode($data['redirect_url'])));
		//forward($data->redirect_url);
	 }
}

function minds_comments_menu($hook, $type, $return, $params) {
	$type = elgg_extract('type', $params, false);
	$pid = elgg_extract('pid', $params, false);
	$id = elgg_extract('id', $params, false);
	
	$owner_guid = elgg_extract('owner_guid', $params, false);
	unset($return);
	
	/**
	 * Delete
	 */
	 if(($owner_guid == elgg_get_logged_in_user_guid() || elgg_is_admin_logged_in()) && elgg_is_logged_in()){
		$delete = array(
			'name' => 'delete',
			'href' => "action/comment/delete?id=$id&type=$type",
			'text' => '&#10062;',
			'title' => elgg_echo('delete'),
			'class' => 'entypo',
			'is_action' => true,
			'priority' => 1000
		);
		$return[] = ElggMenuItem::factory($delete);
	 }

	return $return;
}

/**
 *  Replaces native Elgg comments with hypeAlive Comments
 */
function minds_comments_replacement($hook, $entity_type, $returnvalue, $params) {
	$type = 'entity';
	$pid = $params['entity']->guid;
    	
	$comments =  elgg_view('minds_comments/bar', array(
	    'type' => $type,
	    'pid' => $pid
	));
	
	$comments .= elgg_view('minds_comments/input', array(
	    'type'=>$type,
	    'pid'=>$pid
	));

	
	return $comments;
}
