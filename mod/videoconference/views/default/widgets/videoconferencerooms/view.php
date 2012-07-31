<?php

		// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}

    $num = $vars['entity']->num_display;
  	if(!$num)	$num = 3;

		$videoconference = $page_owner->getObjects('videoconference', $num);

		// If there are any thewire to view, view them
		if (is_array($videoconference) && sizeof($videoconference) > 0) {

			foreach($videoconference as $room) {

				echo elgg_view_entity($room);

			}

		}
?>
