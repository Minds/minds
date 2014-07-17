<?php
/**
 * Elgg metastrngs
 * Functions to manage object metastrings.
 *
 * @package Elgg.Core
 * @subpackage DataModel.MetaStrings
 */


/**
 * @deprecated
 */
function get_metastring_id($string, $case_sensitive = TRUE) {
	return false;
}

/**
 * @deprecated
 */
function get_metastring($id) {
	return false;
}

/**
 * @deprecated 
 */
function delete_orphaned_metastrings() {
	return false;
}

/**
 * @deprecated 
 */
function elgg_get_metastring_based_objects($options) {
	return false;
}

/**
 * @deprecated
 */
function elgg_get_metastring_sql($table, $names = null, $values = null){
	return false;
}

/**
 * @return 
 */
function elgg_normalize_metastrings_options(array $options = array()) {
	return false;
}

/**
 * @return
 */
function elgg_set_metastring_based_object_enabled_by_id($id, $enabled, $type) {
	return false;
}

/**
 * @deprecated 
 */
function elgg_batch_metastring_based_objects(array $options, $callback, $inc_offset = true) {
	return false;
}

/**
 * @deprecated 
 */
function elgg_get_metastring_based_object_from_id($id, $type) {
	return false;	
}

/**
 * @deprecated 
 */
function elgg_delete_metastring_based_object_by_id($id, $type) {
	return false;
}

/**
 * @deprecated
 */
function elgg_entities_get_metastrings_options($type, $options) {
	return false;
}
