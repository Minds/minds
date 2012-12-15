<div class="pftn_list_title">
	<h2><?php echo elgg_echo('suggested_friends'); ?></h2>
</div>
<?php echo elgg_view('suggested_friends/people', array('people' => $vars['people'])); ?>