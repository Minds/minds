<?php

/**
 * Prepare the edit form variables
 *
 * @param ElggObject $livestreaming A livestreaming object.
 * @return array
 */
function livestreaming_prepare_form_vars($livestreaming = null) {
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
		'entity' => $livestreaming,
	);

	if ($livestreaming) {
		foreach (array_keys($values) as $field) {
			if (isset($livestreaming->$field)) {
				$values[$field] = $livestreaming->$field;
			}
		}
	}

	if (elgg_is_sticky_form('livestreaming')) {
		$sticky_values = elgg_get_sticky_values('livestreaming');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('livestreaming');

	return $values;
}
