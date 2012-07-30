<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 *
 * @uses ElggEntity $vars['entity'] The entity to comment on
 * @uses bool       $vars['inline'] Show a single line version of the form?
 */


if (isset($vars['entity']) && elgg_is_logged_in()) {
	
	$inline = elgg_extract('inline', $vars, false);
	
	if ($inline) {
		$icon = elgg_view_entity_icon(elgg_get_logged_in_user_entity(), 'tiny');
		$textbox = elgg_view('input/text', array('name' => 'generic_comment', 'class'=>'comments inline'));
		echo elgg_view_image_block($icon, $textbox);
		echo elgg_view('input/submit', array('value' => elgg_echo('comment'), 'class' => 'hidden'));
	} else {
?>
	<div>
		<label><?php echo elgg_echo("generic_comments:add"); ?></label>
		<?php echo elgg_view('input/longtext', array('name' => 'generic_comment')); ?>
	</div>
	<div class="elgg-foot">
<?php
		echo elgg_view('input/submit', array('value' => elgg_echo("generic_comments:post")));
?>
	</div>
<?php
	}
	
	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID()
	));
}
