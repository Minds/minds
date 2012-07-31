<?php

/**
 * Prepare the edit form variables
 *
 * @param ElggObject $videoconsultation A videoconsultation object.
 * @return array
 */
function videoconsultation_prepare_form_vars($videoconsultation = null) {
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
		'entity' => $videoconsultation,
	);

	if ($videoconsultation) {
		foreach (array_keys($values) as $field) {
			if (isset($videoconsultation->$field)) {
				$values[$field] = $videoconsultation->$field;
			}
		}
	}

	if (elgg_is_sticky_form('videoconsultation')) {
		$sticky_values = elgg_get_sticky_values('videoconsultation');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('videoconsultation');

	return $values;
}
