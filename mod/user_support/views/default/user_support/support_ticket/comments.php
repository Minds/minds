<?php

	/**
	 * Show a comment listing
	 *
	 * @uses $vars["entity"] the entity for the comments
	 * @uses $vars["show_add_form"] show a comment form
	 * @uses $vars["class"] a class for the wrapper
	 *
	 */

	$entity = elgg_extract("entity", $vars);
	$show_add_form = (bool) elgg_extract("show_add_form", $vars, true);
	
	if ($id = elgg_extract("id", $vars, "")) {
		$id = "id='" . $id . "'";
	}
	
	$class = "elgg-comments";
	if ($additional_class = elgg_extract("class", $vars)) {
		$class .= " " . $additional_class;
	}
	
	// work around for deprecation code in elgg_view()
	unset($vars["internalid"]);
	
	echo "<div $id class='" . $class . "'>";
	
	$options = array(
		"guid" => $entity->getGUID(),
		"annotation_name" => "generic_comment"
	);
	
	if ($html = elgg_list_annotations($options)) {
		echo "<h3>" . elgg_echo("comments") . "</h3>";
		echo $html;
	}
	
	if ($show_add_form) {
		echo elgg_view_form("user_support/support_ticket/comment", array(), $vars);
	}
	
	echo "</div>";
