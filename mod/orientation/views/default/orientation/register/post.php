<?php 
$user = elgg_get_logged_in_user_entity();
?>
<div class="blurb">
	Post a status to world. Pick which networks you want it to send to at once.
</div>

<?php echo elgg_view('forms/deck_river/post', array(), array()); ?>

<div style="clear:both; margin:24px;"></div>
