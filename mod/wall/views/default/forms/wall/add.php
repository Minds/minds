<?php
/**
 * Wall add form body
 *
 * @uses $vars['post']
 */

//elgg_load_js('elgg.thewire');


elgg_load_js('elgg.wall');

echo elgg_view('input/plaintext', array(
	'name' => 'body',
	'class' => 'mtm',
	'id' => 'wall-textarea',
));

?>
<div id="wall-characters-remaining">
	<span>1000</span> <?php echo elgg_echo('wall:charleft'); ?>
</div>
<div class="elgg-foot mts">
<?php

echo elgg_view('input/hidden', array(
	'name' => 'to_guid',
	'value' => $vars['to_guid']
));

echo elgg_view('input/submit', array(
	'value' => $text,
	'id' => 'wall-submit-button',
));
?>
</div>