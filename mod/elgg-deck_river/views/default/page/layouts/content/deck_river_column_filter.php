<?php
/*
 * filter for each colum
 */

$column_settings = elgg_extract('column_settings', $vars);

$filter = '';
// create checkboxes array
$types_value = array();
$registered_entities = elgg_get_config('registered_entities');
$types_label[elgg_echo('deck_river:filter:all')] = 'All';

if (!$column_settings['types_filter'] && !$column_settings['subtypes_filter'] || $column_settings['types_filter'] == 'All' ) $types_value[] = 'All';

if (!empty($registered_entities)) {
	foreach ($registered_entities as $type => $subtypes) {
		// subtype will always be an array.
		if (!count($subtypes)) {
			$label = elgg_echo("item:$type");
			$types_label[$label] .= $type;
			if (in_array($type, $column_settings['types_filter'])) $types_value[] = $type;
		} else {
			foreach ($subtypes as $subtype) {
				$label = elgg_echo("item:$type:$subtype");
				$subtypes_label[$label] .= $subtype;
				if (in_array($subtype, $column_settings['subtypes_filter'])) $subtypes_value[] = $subtype;
			}
		}
	}

	// merge keys defined by admin
	$keys_to_merge = explode(',', elgg_get_plugin_setting('keys_to_merge', 'elgg-deck_river'));
	foreach ($keys_to_merge as $key => $value ) {
		$key_master = explode('=', $value);
		foreach ($types_label as $k => $v) {
			if ($v == $key_master[1]) unset($types_label[$k]);
		}
		foreach ($subtypes_label as $k => $v) {
			if ($v == $key_master[1]) unset($subtypes_label[$k]);
		}
	}

	// types
	// Don't need to show user and group for mention column and group column
	$types_label = array_flip($types_label);
	if (in_array($column_settings['type'], array('group', 'groups'))) unset($types_label['user']);
	if (in_array($column_settings['type'], array('mention', 'group_mention'))) $types_label = array_slice($types_label, 0, 1);
	$types_label = array_flip($types_label);

	$filter .= '<ul class="elgg-input-checkboxes elgg-vertical clearfloat pbs phs">';
	foreach ($types_label as $label => $type) {
		$rand = rand();
		$filter .= '<li class="filter-checkbox float-alt">' . elgg_view('input/checkbox', array(
							'id' => "cb-$rand",
							'class' => 'types',
							'value' => $type,
							'checked' => in_array('All', $types_value) || in_array($type, $types_value)
						));
		$filter .= '<label class="gwfb t25 '. ($type == 'All' ? '' : 'tooltip s') . ' link '.$type.'" title="'.$label.'" for="cb-'.$rand.'">'.$label.'</label></li>';
	}
	$filter .= '</ul>';

	// subtypes
	$filter .= '<ul class="elgg-input-checkboxes elgg-vertical clearfloat pbs phs">';
	foreach ($subtypes_label as $label => $subtype) {
		$rand = rand();
		$filter .= '<li class="filter-checkbox float">' . elgg_view('input/checkbox', array(
							'id' => "cb-$rand",
							'class' => 'subtypes',
							'value' => $subtype,
							'checked' => in_array('All', $types_value) || in_array($subtype, $subtypes_value)
						));
		$filter .= '<label class="gwfb t25 tooltip s link '.$subtype.'" title="'.$label.'" for="cb-'.$rand.'">'.$label.'</label></li>';
	}
	$filter .= '</ul>';

	$filter .= '<ul class="clearfloat pas">' . elgg_view('output/url', array(
		'href' => "#",
		'text' => elgg_echo('search'),
		'class' => 'elgg-button elgg-button-submit noajaxified'
	)) . '<div class="close-filter pas float-alt link">▲</div></ul>';

}

echo <<<HTML
<ul class="column-filter mbs pvs hidden">
	$filter
</ul>
HTML;

