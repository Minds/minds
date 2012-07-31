<?php

/**
 * Prepare the edit form variables
 *
 * @param ElggObject $videochat A videochat object.
 * @return array
 */
function videochat_prepare_form_vars($videochat = null) {
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
		'entity' => $videochat,
	);

	if ($videochat) {
		foreach (array_keys($values) as $field) {
			if (isset($videochat->$field)) {
				$values[$field] = $videochat->$field;
			}
		}
	}

	if (elgg_is_sticky_form('videochat')) {
		$sticky_values = elgg_get_sticky_values('videochat');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('videochat');

	return $values;
}
