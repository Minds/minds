<?php

/**
 * Prepare the edit form variables
 *
 * @param ElggObject $videoconference A videoconference object.
 * @return array
 */
function videoconference_prepare_form_vars($videoconference = null) {
	// input names => defaults
	$values = array(
		'title' => get_input('title', ''),
		'address' => get_input('address', ''),
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'shares' => array(),
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $videoconference,
	);

	if ($videoconference) {
		foreach (array_keys($values) as $field) {
			if (isset($videoconference->$field)) {
				$values[$field] = $videoconference->$field;
			}
		}
	}

	if (elgg_is_sticky_form('videoconference')) {
		$sticky_values = elgg_get_sticky_values('videoconference');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('videoconference');

	return $values;
}
