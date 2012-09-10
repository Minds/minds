<?php

	/**
	 * Elgg Poll plugin
	 * @package Elggpoll
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @Original author John Mellberg
	 * website http://www.syslogicinc.com
	 * @Modified By Team Webgalli to work with ElggV1.5
	 * www.webgalli.com or www.m4medicine.com
	 */
	 

		$owner = $vars['entity']->getOwnerEntity();
		$friendlytime = friendly_time($vars['entity']->time_created);
		$responses = $vars['entity']->countAnnotations('vote');
		
		/**/
		$icon = elgg_view(
				"profile/icon", array(
										'entity' => $owner,
										'size' => 'small',
									  )
			);
		
		
		$info = "<p>" . elgg_echo('poll') . ": <a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->question}</a></p>";
		$info .= "<p>{$responses} ".elgg_echo('polls:votes')."</p>";
		$info .= "<p><a href=\"{$owner->getURL()}\">{$owner->name}</a> {$friendlytime}</p>";

		//display
		echo "<div class=\"poll_gallery\">";
		echo "<div class=\"poll_gallery_icon\">" . $icon . "</div>";
		echo "<div class=\"poll_gallery_content\">" . $info . "</div>";
		echo "</div>";


?>