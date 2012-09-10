<?php

/**
 * Elgg poll individual widget view
 *  
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 *
 * @uses $vars['entity'] Optionally, the poll post to view
*/
elgg_load_js('elgg.polls');
?>
<h3><?php echo $vars['entity']->question; ?></h3>
<div id="poll-container-<?php echo $vars['entity']->guid; ?>" class="poll_post">
	<?php echo elgg_view('polls/poll_widget_content',$vars); ?>
</div>
	