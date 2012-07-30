<?php
/**
 * Populate image lists for current photo albums
 */

$album_subtype_id = get_subtype_id('object', 'album');

global $DB_QUERY_CACHE, $DB_PROFILE, $ENTITY_CACHE, $CONFIG;
$album_guids = mysql_query("SELECT guid FROM {$CONFIG->dbprefix}entities WHERE subtype = $album_subtype_id");
while ($guid_obj = mysql_fetch_object($album_guids)) {
	$DB_QUERY_CACHE = $DB_PROFILE = $ENTITY_CACHE = array();

	$album = get_entity($guid_obj->guid);
	$images = elgg_get_entities(array(
		"type" => "object",
		"subtype" => "image",
		"container_guid" => $album->guid,
		"limit" => ELGG_ENTITIES_NO_VALUE,
	));
	$image_list = array();
	foreach ($images as $image) {
		$image_list[] = $image->guid;
	}

	$album->prependImageList($image_list);
}

