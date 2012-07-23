<?php
/**
 * Register the ElggChat class for the object/chat subtype
 */

if (get_subtype_id('object', 'chat')) {
	update_subtype('object', 'chat', 'ElggChat');
} else {
	add_subtype('object', 'chat', 'ElggChat');
}
