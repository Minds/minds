<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 * @uses $vars['class']         Optional additional class for the div
 */

$show_add_form = elgg_extract('show_add_form', $vars, true);

$id = '';
if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}

$class = 'elgg-comments';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// work around for deprecation code in elgg_view()
unset($vars['internalid']);

echo "<div $id class=\"$class\">";

$guid = $vars['entity']->getGUID();


$options = array(
		'types' =>	'object',
		'subtype' => 'comment',
		'metadata_name_value_pairs' => array('name'=>'entity_guid', 'value' => $guid),
		'limit' => 0,
		'order_by' => 'time_created desc'
	);
$html = elgg_list_entities_from_metadata($options);
if ($html) {
	echo '<h3>' . elgg_echo('comments') . '</h3>';
	echo $html;
}

if ($show_add_form) {
	$form_vars = array('name' => 'elgg_add_comment');
	echo elgg_view_form('comments/add', $form_vars, $vars);
}

echo '</div>';
