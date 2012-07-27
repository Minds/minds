<?php

/**
 * Helper functions to manipulate entities
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category Forms
 * @category Framework Entities Library
 */

/**
 * Set priority of an element in a list
 *
 * @see ElggEntity::$priority
 *
 * @param ElggEntity $entity
 * @return bool
 */
function hj_framework_set_entity_priority($entity, $priority = null) {
	if ($priority) {
		$entity->priority = $priority;
		return true;
	}
	$count = elgg_get_entities(array(
		'type' => $entity->getType(),
		'subtype' => $entity->getSubtype(),
		'owner_guid' => $entity->owner_guid,
		'container_guid' => $entity->container_guid,
		'count' => true
			));

	if (!$entity->priority)
		$entity->priority = $count + 1;

	return true;
}

/**
 * Get a list of entities sorted by ElggEntity::$priority
 *
 * @param string $type
 * @param string $subtype
 * @param int $owner_guid
 * @param int $container_guid
 * @param int $limit
 * @return array An array of ElggEntity
 */
function hj_framework_get_entities_by_priority($type, $subtype, $owner_guid = NULL, $container_guid = NULL, $limit = 0, $offset = 0) {
	$db_prefix = elgg_get_config('dbprefix');
	$entities = elgg_get_entities(array(
		'type' => $type,
		'subtype' => $subtype,
		'owner_guid' => $owner_guid,
		'container_guid' => $container_guid,
		'limit' => $limit,
		'offset' => $offset,
		'joins' => array("JOIN {$db_prefix}metadata as mt on e.guid = mt.entity_guid
                      JOIN {$db_prefix}metastrings as msn on mt.name_id = msn.id
                      JOIN {$db_prefix}metastrings as msv on mt.value_id = msv.id"
		),
		'wheres' => array("((msn.string = 'priority'))"),
		'order_by' => "CAST(msv.string AS SIGNED) ASC"
			));
	return $entities;
}

function hj_framework_get_entities_from_metadata_by_priority($type, $subtype, $owner_guid = NULL, $container_guid = NULL, $metadata_name_value_pairs = null, $limit = 0, $offset = 0, $count = false) {
	if (is_array($metadata_name_value_pairs)) {
		$db_prefix = elgg_get_config('dbprefix');
		$entities = elgg_get_entities_from_metadata(array(
			'type' => $type,
			'subtype' => $subtype,
			'owner_guid' => $owner_guid,
			'container_guid' => $container_guid,
			'metadata_name_value_pairs' => $metadata_name_value_pairs,
			'limit' => $limit,
			'offset' => $offset,
			'count' => $count,
			'joins' => array("JOIN {$db_prefix}metadata as mt on e.guid = mt.entity_guid
                      JOIN {$db_prefix}metastrings as msn on mt.name_id = msn.id
                      JOIN {$db_prefix}metastrings as msv on mt.value_id = msv.id"
			),
			'wheres' => array("((msn.string = 'priority'))"),
			'order_by' => "CAST(msv.string AS SIGNED) ASC"
				));
	} else {
		$entities = hj_framework_get_entities_by_priority($type, $subtype, $owner_guid, $container_guid, $limit);
	}
	return $entities;
}

/**
 * Get a DataPattern for a given hf entity
 *
 * @param string $type
 * @param string $subtype
 * @return hjForm
 */
function hj_framework_get_data_pattern($type, $subtype, $handler = null) {
	if ($container && elgg_instanceof($container)) {
		$subtype = $container->getSubtype();
	}
	$forms = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'hjform',
		'metadata_name_value_pairs' => array(
			array(
				'name' => 'subject_entity_type',
				'value' => $type
			),
			array(
				'name' => 'subject_entity_subtype',
				'value' => $subtype
			),
			array(
				'name' => 'handler',
				'value' => $handler
			)
			)));
	return $forms[0];
}

function hj_framework_extract_params_from_entity($entity, $params = array(), $context = null) {
	$return = array();

	if ($context) {
		elgg_push_context($context);
	} else {
		$context = elgg_get_context();
	}

	if (elgg_instanceof($entity)) {

		$container = $entity->getContainerEntity();
		$owner = $entity->getOwnerEntity();
//        $form_guid = get_input('f', $entity->data_pattern);
		$form_guid = $entity->data_pattern;
		$form = get_entity($form_guid);
		if (elgg_instanceof($form)) {
			$fields = $form->getFields();
			$handler = $form->handler;
		}
		$widget = get_entity($entity->widget);

		$entity_params = array(
			'entity_guid' => $entity->guid,
			'subject_guid' => $entity->guid,
			'container_guid' => $container->guid,
			'owner_guid' => $owner->guid,
			'form_guid' => $form->guid,
			'widget_guid' => $widget->guid,
			'type' => $entity->getType(),
			'subtype' => $entity->getSubtype(),
			'context' => $context,
			'handler' => $handler,
			'event' => 'update'
		);

		$params = array_merge($entity_params, $params);
	}
	return $params;
}

function hj_framework_extract_params_from_url() {

	if ($params = get_input('params')) {
		return hj_framework_extract_params_from_params($params);
	}

	$context = get_input('context');
	if (!empty($context)) {
		elgg_push_context($context);
	} else {
		$context = elgg_get_context();
	}

	$section = get_input('subtype');
	if (empty($section)) {
		$section = "hj{$context}";
	}

	$handler = get_input('handler');
	if (empty($handler)) {
		$handler = '';
	}

	$subject_guid = get_input('subject_guid');
	$subject = get_entity($subject_guid);

	if ($entity_guid = get_input('entity_guid')) {
		$entity = get_entity($entity_guid);
		return hj_framework_extract_params_from_entity($entity, $params, $context);
	}

	$container_guid = get_input('container_guid');
	$container = get_entity($container_guid);
	if (!elgg_instanceof($container)) {
		$container = elgg_get_page_owner_entity();
	}

	$owner_guid = get_input('owner_guid');
	if (!empty($owner_guid)) {
		$owner = get_entity($owner_guid);
	} else if (elgg_instanceof($container)) {
		$owner = $container->getOwnerEntity();
	} else if (elgg_is_logged_in()) {
		$owner = elgg_get_logged_in_user_entity();
	} else {
		$owner = elgg_get_site_entity();
	}

	$form_guid = get_input('form_guid');
	$form = get_entity($form_guid);

	if (!elgg_instanceof($form)) {
		$form = hj_framework_get_data_pattern('object', $section, $handler);
	}
	if (elgg_instanceof($form)) {
		$fields = $form->getFields();
	}

	$widget_guid = get_input('widget_guid');
	$widget = get_entity($widget_guid);

	$url_params = array(
		'subject_guid' => $subject->guid,
		'container_guid' => $container->guid,
		'owner_guid' => $owner->guid,
		'form_guid' => $form->guid,
		'widget_guid' => $widget->guid,
		'subtype' => $section,
		'context' => $context,
		'handler' => $handler,
		'event' => 'create'
	);

	return $params;
}

function hj_framework_extract_params_from_params($params) {

	$context = $params['context'];
	if (!empty($context)) {
		elgg_push_context($context);
	} else {
		$context = elgg_get_context();
	}

	$section = $params['subtype'];
	if (empty($section)) {
		$section = "hj{$context}";
	}

	$handler = $params['handler'];
	if (empty($handler)) {
		$handler = '';
	}

	if (!$subject_guid = $params['subject_guid']) {
		$subject_guid = $params['entity_guid'];
	}
	$subject = get_entity($subject_guid);

	$container_guid = $params['container_guid'];
	$container = get_entity($container_guid);
	if (!elgg_instanceof($container)) {
		$container = elgg_get_page_owner_entity();
	}

	$owner_guid = $params['owner_guid'];
	if (!empty($owner_guid)) {
		$owner = get_entity($owner_guid);
	} else if (elgg_instanceof($container)) {
		$owner = $container->getOwnerEntity();
	} else if (elgg_is_logged_in()) {
		$owner = elgg_get_logged_in_user_entity();
	} else {
		$owner = elgg_get_site_entity();
	}

	$form_guid = $params['form_guid'];
	$form = get_entity($form_guid);
	if (!elgg_instanceof($form)) {
		$form = hj_framework_get_data_pattern('object', $section, $handler);
	}
	if (elgg_instanceof($form)) {
		$fields = $form->getFields();
	}

	$widget_guid = $params['widget_type'];
	$widget = get_entity($widget_guid);

	$new_params = array(
		'subject_guid' => $subject->guid,
		'container_guid' => $container->guid,
		'owner_guid' => $owner->guid,
		'form_guid' => $form->guid,
		'widget_guid' => $widget->guid,
		'subtype' => $section,
		'context' => $context,
		'handler' => $handler,
		'event' => 'create'
	);

	$params = array_merge($new_params, $params);
	return $params;
}

function hj_framework_http_build_query($params) {
	if (isset($params['params'])) {
		$params = $params['params'];
	}
	foreach ($params as $key => $param) {
		if (isset($params[$key]) && !elgg_instanceof($param)) {
			$url_params['params'][$key] = $param;
		}
	}
	return http_build_query($url_params);
}

function hj_framework_json_query($params) {
	if (isset($params['params'])) {
		$params = $params['params'];
	}
	foreach ($params as $key => $param) {
		if (isset($params[$key]) && !elgg_instanceof($param)) {
			$url_params['params'][$key] = $param;
		}
	}
	return json_encode($url_params);
}

function hj_framework_get_email_url() {
	$extract = hj_framework_extract_params_from_url();
	$subject = elgg_extract('subject', $extract);

	if (elgg_instanceof($subject)) {
		return $subject->getURL();
	} else {
		return elgg_get_site_url();
	}
}