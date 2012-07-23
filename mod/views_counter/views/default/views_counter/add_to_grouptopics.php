<?php
	/**
	 * @file views/default/views_counter/add_to_grouptopics.php
	 * @brief The groupforumtopic subtype is a common subtype that would be handled by the default system, but the groups plugin is a very bad plugin that do not follow a lot of the elgg patterns
	 */

	// The default elgg system set the $vars['full'] as true always when exhibiting an entity on the page
	$vars['full'] = true;
	
	echo elgg_view('views_counter',$vars);
?>