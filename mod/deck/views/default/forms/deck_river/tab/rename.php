<?php
/**
 * rename tab popup
 *
 * @package elgg-deck_river
 */

$filter_context = elgg_extract('filter_context', $vars);

echo elgg_echo('deck_river:rename_tab_title') . '<br>';

echo elgg_view('input/text', array(
	'name' => 'tab_name',
	'value' => ucfirst($filter_context),
	'class' => 'mts mrm'
));

echo elgg_view('input/hidden', array(
	'name' => 'old_tab_name',
	'value' => $filter_context,
));

echo elgg_view('input/submit', array(
		'value' => 'save',
		'name' => elgg_echo('save'),
		'class' => 'elgg-button-submit mtm mlm noajaxified'
));
