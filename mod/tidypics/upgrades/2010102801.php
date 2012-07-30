<?php
/**
 * Convert river entries for tags to be tagger-tagee-annotation from
 * image-tagee
 */

$album_subtype_id = get_subtype_id('object', 'album');

global $DB_QUERY_CACHE, $DB_PROFILE, $ENTITY_CACHE, $CONFIG;
$query = "SELECT * FROM {$CONFIG->dbprefix}river WHERE view = 'river/object/image/tag'";
$river_items = mysql_query($query);
while ($item = mysql_fetch_object($river_items)) {
	$DB_QUERY_CACHE = $DB_PROFILE = array();

	// find the annotation for this river item
	$annotations = get_annotations($item->subject_guid, '', '', 'phototag', '', 0, 999);
	foreach ($annotations as $annotation) {
		$tag = unserialize($annotation->value);
		if ($tag->type === 'user') {
			if ($tag->value == $item->object_guid) {
				$update = "UPDATE {$CONFIG->dbprefix}river SET subject_guid = $annotation->owner_guid, annotation_id = $annotation->id where id = $item->id";
				mysql_query($update);
			}
		}
	}
}