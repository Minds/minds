<?php
elgg_load_library('elgg:polls');

$poll = elgg_extract('entity', $vars);

if($msg = elgg_extract('msg', $vars)) {
	echo '<p>'.$msg.'</p>';
}

if (elgg_is_logged_in()) {
	$user_guid = elgg_get_logged_in_user_guid();
	$can_vote = !polls_check_for_previous_vote($poll, $user_guid);
	
} else {
	$results_display = "block";
	$poll_display = "none";
	$show_text = elgg_echo('polls:show_poll');
	$voted_text = elgg_echo('polls:login');
	$can_vote = FALSE;
}
?>
<div id="poll-post-body-<?php echo $poll->guid; ?>" class="poll_post_body" style="display:<?php echo $results_display ?>;">
<?php echo elgg_view('polls/results_for_widget', array('entity' => $poll)); ?>
</div>

