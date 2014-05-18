<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');
header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);

		$class = "notification notifier";
		$text = "<span class='$class'>&#59141;</span>";
	
// get unread messages
		$num_notifications = minds\plugin\notifications\notifcations::getCount();
		if ($num_notifications > 0) {
			$class = "notification notifier new";
			$text = "<span class='$class'>&#59141;" .
						"<span class=\"notification-new\">$num_notifications</span>" .
					  "</span>";
		}

		
		echo $text;
?>