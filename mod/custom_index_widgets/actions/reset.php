<?php

		//action_gatekeeper();
		
		global $CONFIG;
		
		$result = reorder_widgets_from_panel(null, null, null, 'custom_index_widgets', 2);
		if ($result) {
			system_message(elgg_echo('widgets:save:success'));
		} else {
			register_error(elgg_echo('widgets:save:failure'));
		}
		forward($_SERVER['HTTP_REFERER']);

?>