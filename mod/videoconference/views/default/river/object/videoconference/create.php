<?php

	$object = get_entity($vars['item']->object_guid);
	$descx = explode("^", $object->description);
  $desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . $vars['url'] . 'videoconference/$1">$1</a>',$descx[0]);
  $desc = parse_urls($desc);

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $desc,
));

?>
