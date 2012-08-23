<?php

	/**
	 * Elgg poll listing
	 * 
	 * @package Elggpoll
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author John Mellberg
	 * @copyright John Mellberg 2009
	 */

		$owner = $vars['entity']->getOwnerEntity();
		$container = get_entity($vars['entity']->container_guid);
		$friendlytime = elgg_get_friendly_time($vars['entity']->time_created);
		$responses = $vars['entity']->countAnnotations('vote');
		//$icon = "";
		/**/
		$icon = elgg_view(
				"profile/icon", array(
										'entity' => $owner,
										'size' => 'small',
									  )
			);
		
		$info = "<a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->question}</a>";
		if ($container instanceOf ElggGroup) {
			$group_bit = '<a href="'.$container->getUrl().'">'.$container->name.'</a>';
			$info .= " ".sprintf(elgg_echo('polls:group_identifier'),$group_bit);
		}
		$info .= "<br />{$responses} ".elgg_echo('polls:votes');
		$info .= "<p class=\"owner_timestamp\"><a href=\"{$owner->getURL()}\">{$owner->name}</a> {$friendlytime}</p>";
		echo elgg_view_image_block($icon,$info);

?>