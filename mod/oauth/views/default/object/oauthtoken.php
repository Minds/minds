<?php

$tokEnt = $vars['entity'];

global $CONFIG;

// copied and adapted from object/default

$consumEnt = oauth_lookup_consumer_entity($tokEnt->consumerKey);

$title = $consumEnt->name;

$controls = '';
if ($tokEnt->canEdit()) {
	$controls .= '(' . elgg_view('output/confirmlink', array(
				       'href' => $CONFIG->wwwroot . 'action/oauth/revoke?guid=' . $tokEnt->getGUID(),
				       'text' => elgg_echo('oauth:token:revoke'),
				       'confirm' => elgg_echo('deleteconfirm')
				       ))
		. ')';
}

$info = '<div><p><b>' . $title . '</b> ' . $controls . ' </p>' . $consumEnt->desc . "</div>\n";

if ($consumEnt->revA) {
	$icon = '<img src="' . $CONFIG->wwwroot . 'mod/oauth/graphics/oauth_shiny_small.png" title="1.0a" />';
} else {
	$icon = '<img src="' . $CONFIG->wwwroot . 'mod/oauth/graphics/oauth_small.png" title="1.0" />';
}

$info .= '<div>';
if ($tokEnt->canEdit()) {
	if ($tokEnt->accessToken) {
		$info .= '<b>' . elgg_echo('oauth:token:access') . ':</b> ' . $tokEnt->accessToken . '<br />';var_dump($tokEnt->accessToken);
	} else if ($tokEnt->requestToken) {
		$info .= '<i>' . elgg_echo('oauth:token:request') . ':</i> ' . $tokEnt->requestToken . '<br />';
	}
	$info .= '<i>' . elgg_echo('oauth:secret') . ':</i> ' . $tokEnt->secret . '<br />';
} else {
	if ($tokEnt->accessToken) {
		$info .= '<b>' . elgg_echo('oauth:token:access') . '</b> <br />';
	} else if ($tokEnt->requestToken) {
		$info .= '<i>' . elgg_echo('oauth:token:request') . '</i> <br />';
	}
}	
$info .= "</div>\n";

echo elgg_view_image_block($icon, $info);

?>
