<?php
$user = $vars['entity'];
$actions = elgg_extract('action', $vars['menu'], null);
$main = elgg_extract('default', $vars['menu'], null);
$admin = elgg_extract('admin', $vars['menu'], null);

echo '<ul class="elgg-menu elgg-menu-hovercard">';

// admin
if (elgg_is_admin_logged_in() && $admin) {
	echo '<li>';
	foreach($admin as $key => $item){
		if(in_array($item->getName(), array('profile:edit','ban','delete')))
			unset($admin[$key]);
	}
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu ',
		'items' => $admin,
	));
	
	echo '</li>';
}

echo "</ul>";
