<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');

		$class = "elgg-icon elgg-icon-tag";
		$text = "<span class='$class'></span>";
	
// get unread messages
		$num_notifications = (int)notifications_count_unread();
		if ($num_notifications != 0) {
			$text .= "<span class=\"notification-new\">$num_notifications</span>";
		}
		
		echo $text;
?>