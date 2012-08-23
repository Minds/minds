<?php
$poll = elgg_extract('entity', $vars);
if ($poll) {
	$guid = $poll->guid;
} else  {
	$guid = 0;
}

$question = $vars['fd']['question'];
$tags = $vars['fd']['tags'];
$access_id = $vars['fd']['access_id'];

$question_label = elgg_echo('polls:question');
$question_textbox = elgg_view('input/text', array('name' => 'question', 'value' => $question));

$responses_label = elgg_echo('polls:responses');
$responses_control = elgg_view('polls/input/choices',array('poll'=>$poll));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array('name' => 'tags', 'value' => $tags));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));

$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));
$submit_input .= ' '.elgg_view('input/button', array('name' => 'cancel', 'id' => 'polls_edit_cancel', 'type'=> 'button', 'value' => elgg_echo('cancel')));

$poll_front_page = elgg_get_plugin_setting('front_page','polls');

if(elgg_is_admin_logged_in() && ($poll_front_page == 'yes')) {
	$front_page_input = '<p>';
	if ($vars['fd']['front_page']) {
		$front_page_input .= elgg_view('input/checkbox',array('name'=>'front_page','value'=>1,'checked'=>'checked'));
	} else {
		$front_page_input .= elgg_view('input/checkbox',array('name'=>'front_page','value'=>1));
	}
	$front_page_input .= elgg_echo('polls:front_page_label');
	$front_page_input .= '</p>';
} else {
	$front_page_input = '';
}

if (isset($vars['entity'])) {
	$entity_hidden = elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
} else {
	$entity_hidden = '';
}

$entity_hidden .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));

echo <<<__HTML
		<p>
			<label>$question_label</label><br />
			$question_textbox
		</p>
		<p>
			<label>$responses_label</label><br />
			$responses_control
		</p>
		<p>
			<label>$tag_label</label><br />
			$tag_input
		</p>
		$front_page_input
		<p>
			<label>$access_label</label><br />
			$access_input
		</p>
		<p>
		$entity_hidden
		$submit_input
		</p>
__HTML;

		// TODO - move this JS
		?>
<div></div>
<script type="text/javascript">
$('#polls_edit_cancel').click(
	function() {
		window.location.href="<?php echo $vars['url'].'pg/polls/list/'.(elgg_get_page_owner_entity()->username); ?>";
	}
);
</script>
