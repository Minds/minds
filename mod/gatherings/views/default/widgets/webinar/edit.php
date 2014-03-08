<?php
/**
 * User blog widget edit view
 */

// set default value
if (!isset($vars['entity']->webinar_num)) {
	$vars['entity']->webinar_num = 4;
}

$params = array(
	'name' => 'params[webinar_num]',
	'value' => $vars['entity']->webinar_num,
	'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
);
$dropdown = elgg_view('input/dropdown', $params);

?>
<div>
	<?php echo elgg_echo('webinar:widget:edit:numbertodisplay'); ?>:
	<?php echo $dropdown; ?>
</div>
