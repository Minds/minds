<?php

if(!elgg_is_logged_in()){

	forward();

}

$limit = get_input('limit', 12);
$offset = get_input('offset', '');
$slug = get_input('slug',elgg_get_logged_in_user_entity()->username);
switch($slug){
	
	case 'referred':
		$params['attrs']['namespace'] = 'object:node:referrer:'.elgg_get_logged_in_user_entity()->guid;
		break;
	case 'mine':
		$user = elgg_get_logged_in_user_entity();
	default:
		if(!$user)
			$user = get_user_by_username($slug);
		$params['owner_guid']  = $user->guid;
}


$title_block = elgg_view_title(elgg_echo('Manage nodes'), array('class' => 'elgg-heading-main'));
$buttons = elgg_view_menu('title', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
	
$filter = elgg_view('minds_nodes/nav');

$header = <<<HTML
<div class="elgg-head clearfix">
	$title_block
	$buttons
</div>
	$filter
HTML;

$params = array_merge($params, array(
	'type'=>'object', 
	'subtype'=>'node',
	'limit'=>$limit, 
	'offset'=>$offset
));

$content = elgg_list_entities($params);


$body = elgg_view_layout("one_column", array(	
					'header' => $header,
					'content'=> $content,
				));

echo elgg_view_page($title,$body); 
