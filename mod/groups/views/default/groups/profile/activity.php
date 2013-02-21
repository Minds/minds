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


elgg_load_js('elgg.wall');
			
$content .= elgg_view_form('wall/add', array('name'=>'elgg-wall-news-groups'), array('to_guid'=> $group->guid));

/*$db_prefix = elgg_get_config('dbprefix');
$content .= elgg_list_river(array(
	'limit' => 25,
	'pagination' => true,
	'joins' => array("JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid"),
	'wheres' => array("(e1.container_guid = $group->guid)"),
));*/
$entities = elgg_get_entities(array('container_guid'=>$group->guid, 'limit'=>0));
foreach($entities as $entity){
	$entity_guids[] = $entity->getGUID();
}
if(count($entity_guids) > 0){
	$content .= minds_elastic_list_news(array('object_guids'=>$entity_guids, 'limit' => 25,
	'pagination' => true));
}

//echo elgg_view_module('wall', null, $content);

if (!$content) {
	$content = '<p>' . elgg_echo('groups:activity:none') . '</p>';
}

echo $content;