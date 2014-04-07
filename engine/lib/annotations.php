<?php
/**
 * Elgg annotations
 * Functions to manage object annotations.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * @deprecated
 */
function row_to_elggannotation($row) {
	return false;
}

/**
 * @deprecated
 */
function elgg_get_annotation_from_id($id) {
	return false;
}

/**
 * @deprecated
 */
function elgg_delete_annotation_by_id($id) {
	return false;
}

/**
 * @deprecated
 */
function create_annotation($entity_guid, $name, $value, $value_type = '',
$owner_guid = 0, $access_id = ACCESS_PRIVATE) {
	return false;
}

/**
 * @deprecated
 */
function update_annotation($annotation_id, $name, $value, $value_type, $owner_guid, $access_id) {
	return false;
}

/**
 * @deprecated
 */
function elgg_get_annotations(array $options = array()) {
	return false;
}

/**
 * @deprecated
 */
function elgg_delete_annotations(array $options) {
	return false;
}

/**
 * @deprecated
 */
function elgg_disable_annotations(array $options) {
	return false;
}

/**
 * @deprecated
 */
function elgg_enable_annotations(array $options) {
	return false;
}

/**
 * @deprecated
 */
function elgg_list_annotations($options) {
	return false;
}

/**
 * Entities interfaces
 */

/**
 * @deprecated
 */
function elgg_get_entities_from_annotations(array $options = array()) {
	return false;
}

/**
 * @deprecated
 */
function elgg_list_entities_from_annotations($options = array()) {
	return false;
}

/**
 * @deprecated
 */
function elgg_get_entities_from_annotation_calculation($options) {
	return false;
}

/**
 * @deprecated
 */
function elgg_list_entities_from_annotation_calculation($options) {
	return false;
}

/**
 * @deprecated
 */
function export_annotation_plugin_hook($hook, $type, $returnvalue, $params) {
	return false;
}

/**
 * @deprecated
 */
function get_annotation_url($id) {
	return false;
}

/**
 * @deprecated
 */
function elgg_annotation_exists($entity_guid, $annotation_type, $owner_guid = NULL) {
	return false;
}

/**
 * @deprecated
 */
function elgg_comment_url_handler(ElggAnnotation $comment) {
	return false;
}

/**
 * @deprecated
 */
function elgg_register_annotation_url_handler($extender_name = "all", $function_name) {
	return false;
}

/**
 * @deprecated
 */
function annotations_test($hook, $type, $value, $params) {
	return false;
}

/**
 * @deprecated
 */
function elgg_annotations_init() {
}