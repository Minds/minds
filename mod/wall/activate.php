<?php
/**
 * Register the WallPost class for the object/wall subtype
 */

if (get_subtype_id('object', 'wallpost')) {
	update_subtype('object', 'wallpost', 'WallPost');
} else {
	add_subtype('object', 'wallpost', 'WallPost');
}
