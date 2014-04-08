<?php
/**
 * Groups latest activity
 *
 * @todo add people joining group to activity
 * 
 * @package Groups
 */

if ($vars['entity']->activity_enable == 'no') {
	return true;
}

$group = $vars['entity'];
if (!$group) {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "groups/activity/$group->guid",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

$is_member = $group->isMember(elgg_get_logged_in_user_entity());
if(!$is_member){
	echo '<h3> Please join this group to post </h3>';
	return true;
}


//elgg_load_js('elgg.wall');
			
$content .= elgg_view_form('deck_river/post',  
	array(	'action'=>'action/deck_river/post/add', 
			'name'=>'elgg-wall-news', 
			'enctype' => 'multipart/form-data'
	),
	array(	'to_guid'=> $group->guid, 
	 		'access_id'=> ACCESS_PRIVATE, 
	 		'hide_accounts'=>true
	)
);

$content  .= elgg_list_river(array('owner_guid'=>$group->guid, 'masonry'=>true, 'list_class'=>'minds-group-list'));

//echo elgg_view_module('wall', null, $content);

if (!$content) {
	$content = '<p>' . elgg_echo('groups:activity:none') . '</p>';
}

echo $content;
