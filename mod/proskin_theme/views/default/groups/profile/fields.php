<?php
/**
 * Group profile fields
 */

$group = $vars['entity'];

$profile_fields = elgg_get_config('group');

echo "<dl class=\"elgg-profile\">";
if (is_array($profile_fields) && count($profile_fields) > 0) {

	foreach ($profile_fields as $key => $valtype) {
		// do not show the name
		if ($key == 'name') {
			continue;
		}

		$value = $group->$key;
		if (empty($value)) {
			continue;
		}

		$options = array('value' => $group->$key);
		if ($valtype == 'tags') {
			$options['tag_names'] = $key;
		}

		echo "<dt>";
		echo elgg_echo("groups:$key");
		echo "</dt><dd>";
		echo elgg_view("output/$valtype", $options);
		echo "</dd>";
	}
}
echo "</dl>";