<?php

/**
 * List most recent pages on group profile page
 */

if ($vars['entity']->webinar_enable != 'no') {
	$context = get_context();
	set_context('search');
	$content = elgg_list_entities(array('types' => 'object',
										'subtypes' => array('webinar'),
										'container_guid' => $vars['entity']->guid,
										'limit' => 5,
										'full_view' => FALSE,
										'pagination' => FALSE));
	set_context($context);
	if ($content) {
		echo "<div class=\"group_widget\">";
		$more_url = "{$vars['url']}pg/webinar/owned/{$vars['entity']->username}/";
		echo "<h2><a href=\"$more_url\">" . elgg_echo("webinar:profilegroup") . "</a></h2>";
	
		echo $content;
		echo "</div>";
	}
}