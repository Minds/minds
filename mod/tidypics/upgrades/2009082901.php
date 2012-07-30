<?php

/********************************************
 *
 * Upgrade from Tidypics 1.5 to 1.6
 *
 *********************************************/

global $CONFIG;

// add image class
$id = get_subtype_id("object", "image");
if ($id != 0) {
	$table = $CONFIG->dbprefix . 'entity_subtypes';
	$result = update_data("UPDATE {$table} set class='TidypicsImage' where id={$id}");
	if (!$result) {
		register_error('unable to update tidypics image class');
	}
}

// add album class
$id = get_subtype_id("object", "album");
if ($id != 0) {
	$table = $CONFIG->dbprefix . 'entity_subtypes';
	$result = update_data("UPDATE {$table} set class='TidypicsAlbum' where id={$id}");
	if (!$result) {
		register_error('unable to update tidypics album class');
	}
}
