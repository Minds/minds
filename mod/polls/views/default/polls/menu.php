<?php

	/**
	 * Elgg hoverover extender for poll
	 *
	 * @package Elggpoll_extended
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author John Mellberg <big_lizard_clyde@hotmail.com>
	 * @copyright John Mellberg - 2009
	 *
	 */

?>

	<p class="user_menu_poll">
		<a href="<?php echo $vars['url']; ?>pg/polls/list/<?php echo $vars['entity']->username; ?>"><?php echo elgg_echo("poll"); ?></a>	
	</p>