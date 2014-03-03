<?php
/**
 * Elgg metadata
 * Functions to manage entity metadata.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Metadata
 */

/**
 * @deprected
 */
function row_to_elggmetadata($row) {
	return;
}

/**
 * @deprecated
 */
function elgg_get_metadata_from_id($id) {
	return;
}

/**
 * @deprecated
 */
function elgg_delete_metadata_by_id($id) {
	return;
}

/**
 * @deprecated
 */
function create_metadata($entity_guid, $name, $value, $value_type = '', $owner_guid = 0){
	return;
}

/**
 * @deprecated
 */
function update_metadata($id, $name, $value, $value_type, $owner_guid, $access_id) {
	return;
}

/**
 * @deprecated
 */
function create_metadata_from_array($entity_guid, array $name_and_values, $value_type, $owner_guid,
$access_id = ACCESS_PRIVATE, $allow_multiple = false) {
	return;
}

/**
 * @deprecated
 */
function elgg_get_metadata(array $options = array()) {
	return;
}

/**
 * @deprecated
 */
function elgg_delete_metadata(array $options) {
	return;
}

/**
 * @deprecated
 */
function elgg_disable_metadata(array $options) {
	return;
}

/**
 * @deprecated
 */
function elgg_enable_metadata(array $options) {
	return;
}

/**
 * ElggEntities interfaces
 */

/**
 * @deprecated
 */
function elgg_get_entities_from_metadata(array $options = array()) {
	return;
}

/**
 * @deprecated
 */
function elgg_get_entity_metadata_where_sql($e_table, $n_table, $names = NULL, $values = NULL,
$pairs = NULL, $pair_operator = 'AND', $case_sensitive = TRUE, $order_by_metadata = NULL,
$owner_guids = NULL) {

	return;
}

/**
 * @deprecated
 */
function elgg_list_entities_from_metadata($options) {
	return;
}

/**
 * Other functions
 */

/**
 * @deprecated
 */
function export_metadata_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	return;
}

/**
 * @deprecated
 */
function string_to_tag_array($string) {
	return;
}

/**
 * @deprecated
 */
function metadata_array_to_values($array) {
	return;
}

/**
 * @deprecated
 */
function get_metadata_url($id) {
	return;
}

/**
 * @deprecated
 */
function register_metadata_as_independent($type, $subtype = '*') {
	return;
}

/**
 * @deprecated
 */
function is_metadata_independent($type, $subtype) {
	return;
}

/**
 * @deprecated 
 */
function metadata_update($event, $object_type, $object) {
	return;
}

/**
 * @deprecated
 */
function elgg_register_metadata_url_handler($extender_name, $function) {
	return;
}

/**
 * @deprecated 
 */
function elgg_get_metadata_cache() {
	return;
}

/**
 * @deprecated
 */
function elgg_invalidate_metadata_cache($action, array $options) {
	return;
}

/**
 * @deprecated 
 */
function metadata_test($hook, $type, $value, $params) {
	return;
}
