<?php
/**
 * Wall add form body
 *
 * @uses $vars['post']
 */

//elgg_load_js('elgg.thewire');


elgg_load_js('elgg.wall');
elgg_load_js('jquery.autosize');

echo elgg_view('input/plaintext', array(
	'name' => 'body',
	'id' => 'wall-textarea',
	'placeholder' => 'Write a new post to the news...'
));
echo elgg_view('input/file', array(
	'name'=> 'attachment',
	'id'=>'attachment'
));

echo elgg_view('input/submit', array(
        'value' => elgg_echo('post'),
        'id' => 'wall-submit-button',
));
?>
<div class="elgg-foot">
<!--	<div id="wall-characters-remaining">
		<span>1000</span> <?php echo elgg_echo('wall:charleft'); ?>
	</div>-->
	<div class="social-post-icons">
		<?php if($vars['to_guid'] == elgg_get_logged_in_user_guid() || !isset($vars['to_guid']) && elgg_get_context() != 'channel') echo elgg_view('minds_social/wall_social_buttons'); ?>
	</div>
<?php

echo elgg_view('input/hidden', array(
	'name' => 'to_guid',
	'value' => $vars['to_guid']
));
echo elgg_view('input/hidden', array(
	'name' => 'ref',
	'value' => $vars['ref'] ? $vars['ref'] : 'wall'
));

?>
</div>
