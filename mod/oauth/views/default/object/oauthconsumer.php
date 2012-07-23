<?php

$consumEnt = $vars['entity'];

global $CONFIG;

// copied and adapted from object/default

$title = $consumEnt->title;
if (!$title) $title = $consumEnt->name;
if (!$title) $title = get_class($consumEnt);

$controls = '';
if ($consumEnt->canEdit()) {
	$controls .= '( <a href="' .  $CONFIG->wwwroot . 'pg/oauth/editconsumer?guid=' . $consumEnt->getGUID() . '">'
		. elgg_echo('oauth:consumer:edit:link') . '</a>'
		. ' |';
	$controls .= ' ' . elgg_view('output/confirmlink', array(
					     'href' => $CONFIG->wwwroot . 'action/oauth/unregister?guid=' . $consumEnt->getGUID(),
					     'text' => elgg_echo('oauth:consumer:unregister'),
					     'confirm' => elgg_echo('deleteconfirm')
					     ))
		. ' )';
}

$info = '<div><p><b>' . $title . '</b> ' . $controls . ' </p>' . $consumEnt->desc . "</div>\n";


$info .= '<div>';

// only show the key and secret to people that we're supposed to
if ($consumEnt->canEdit()) {
	$info .= '<b>' . elgg_echo('oauth:key') . ':</b> ' . $consumEnt->key . '<br />';
	$info .= '<b>' . elgg_echo('oauth:secret') . ':</b> ' . $consumEnt->secret . '<br />';
}

if ($consumEnt->callbackUrl) {
	$info .= '<b>' . elgg_echo('oauth:callback') . ':</b> ' . $consumEnt->callbackUrl . '<br />';
}

if ($consumEnt->revA) {
	$icon = '<img src="' . $CONFIG->wwwroot . 'mod/oauth/graphics/oauth_shiny_small.png" title="1.0a" />';
	$info .= '<b>' . elgg_echo('oauth:version') . ':</b> 1.0a <br />';
} else {
	$icon = '<img src="' . $CONFIG->wwwroot . 'mod/oauth/graphics/oauth_small.png" title="1.0" />';
	$info .= '<b>' . elgg_echo('oauth:version') . ':</b> 1.0 <br />';
}
$info .= elgg_echo('oauth:consumer:registeredby') . ' <a href="' . $consumEnt->getOwnerEntity()->getUrl() . '">' . $consumEnt->getOwnerEntity()->name . '</a><br />';
$info .= '</div>' . "\n";

echo elgg_view_image_block($icon, $info);

?>