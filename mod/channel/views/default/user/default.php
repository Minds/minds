<?php
/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 * @uses $vars['size']   Size of the icon
 */

$entity = $vars['entity'];
$size = elgg_extract('size', $vars, 'large');

$icon = elgg_view_entity_icon($entity, $size, $vars);

// Simple XFN
/*$rel = '';
if (elgg_get_logged_in_user_guid() == $entity->guid) {
	$rel = 'rel="me"';
} elseif (check_entity_relationship(elgg_get_logged_in_user_guid(), 'friend', $entity->guid)) {
	$rel = 'rel="friend"';
}*/

$title = "<a href=\"" . $entity->getUrl() . "\" $rel>" . $entity->name . "</a>";

$metadata = elgg_view_menu('entity', array(
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

if (elgg_in_context('owner_block') || elgg_in_context('widgets')) {
	$metadata = '';
}

if (elgg_get_context() == 'gallery') {
	echo $icon;
} elseif(elgg_get_context() == 'search'){
	$icon = elgg_view_entity_icon($entity, 'medium', $vars);
	 $overview = elgg_view('user/overview', array('entity' => $entity));
        $list_body = "<h2>$title</h2>$overview";
        
        $vars['class'] = 'user';
        echo elgg_view_image_block($icon, $list_body, $vars);

} else  {
	if($size == 'large'){
		echo elgg_view('icon/user/hovercard', array('user'=>$entity, 'show'=>true));
		return true;
	}
	$overview = elgg_view('user/overview', array('entity' => $entity));
	$list_body = "$overview <h2>$title</h2>";
	
	$vars['class'] = 'user';
	echo elgg_view_image_block($icon, $list_body, $vars);
}
