<?php

	/**
	 * Elgg comments add form
	 *
	 * @package Elgg
	 *
	 * @uses ElggEntity $vars["entity"] The entity to comment on
	 * @uses bool       $vars["inline"] Show a single line version of the form?
	 */
	
	$entity = elgg_extract("entity", $vars);

	if (!empty($entity) && elgg_is_logged_in()) {
	
		$inline = elgg_extract("inline", $vars, false);
	
		if ($inline) {
			echo elgg_view("input/text", array("name" => "generic_comment"));
			echo elgg_view("input/submit", array("value" => elgg_echo("comment")));
		} else {
			
			echo "<div>";
			echo "<label>" . elgg_echo("generic_comments:add") . "</label>";
			echo elgg_view("input/longtext", array("name" => "generic_comment"));
			echo "</div>";
			
			echo "<div class='elgg-foot'>";
			echo elgg_view("input/hidden", array("name" => "entity_guid", "value" => $entity->getGUID()));
			echo elgg_view("input/submit", array("value" => elgg_echo("generic_comments:post"), "name" => "submit"));
			if ($entity->getStatus() == UserSupportTicket::OPEN) {
				echo elgg_view("input/submit", array("value" => elgg_echo("user_support:comment_close"), "name" => "submit"));
			}
			echo "</div>";
		}
	}
