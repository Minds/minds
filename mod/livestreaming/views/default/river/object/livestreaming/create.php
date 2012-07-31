<?php

	$object = get_entity($vars['item']->object_guid);

	$user_name = $object->getOwnerEntity()->name;
	$url = $object->getURL();
	$link2 = " <a href=\"" . $object->getURL() . "?live=2\">Watch and Chat</a> | ";
	$link3 = " <a href=\"" . $object->getURL() . "?live=3\">Just Watch Video</a>";

    if (elgg_is_logged_in()) {
          // get name
      $ElggUser = elgg_get_logged_in_user_entity();
      $username=$ElggUser->get("name");			
			if ($username == $user_name) $link1 = " <a href=\"" . $object->getURL() . "?live=1\">Broadcast</a> | ";
    }

    $title = $object->title; 
	   $descx = explode("^", $object->description);
        $desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . $vars['url'] . 'livestreaming/$1">$1</a>',$descx[0]);
        $desc = parse_urls($desc);

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $title. "  (".$desc.")  ".$link1.$link2.$link3,
));

?>
