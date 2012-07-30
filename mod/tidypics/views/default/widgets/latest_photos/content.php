<?php
/**
 * Display the latest photos uploaded by an individual
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

echo elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'image',
	'limit' => $vars['entity']->num_display,
	'owner_guid' => elgg_get_page_owner_guid(),
	'full_view' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'gallery_class' => 'tidypics-gallery-widget',
));
