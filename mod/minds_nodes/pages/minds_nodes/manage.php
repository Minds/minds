<?php

if(!elgg_is_logged_in()){

	forward();

}

$username = get_input('username', elgg_get_logged_in_user_entity()->username);
$user = get_user_by_username($username);

$title_block = elgg_view_title(elgg_echo('Manage nodes'), array('class' => 'elgg-heading-main'));
$buttons = elgg_view_menu('title', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));

$header = <<<HTML
<div class="elgg-head clearfix">
	$title_block$buttons
</div>
HTML;

$limit = get_input('limit', 12);
$offset = get_input('offset', '');
$content = elgg_list_entities(array('type'=>'object', 'subtype'=>'node', 'owner_guid'=>$user->guid,'limit'=>$limit, 'offset'=>$offset));

$body = elgg_view_layout("one_column", array(	
					'header' => $header,
					'content'=> $content,
				));

echo elgg_view_page($title,$body); 
