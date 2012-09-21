<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');
header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);

		$class = "elgg-icon notification notifier";
		$text = "<span class='$class'></span>";
	
// get unread messages
		$num_notifications = (int)notifications_count_unread();
		if ($num_notifications > 0) {
			$class = "elgg-icon notification notifier new";
			$text = "<span class='$class'>" .
						"<span class=\"notification-new\">$num_notifications</span>" .
					  "</span>";
		}

		
		echo $text;
?>