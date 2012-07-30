<?php
/**
 * List albums in a widget
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$options = array(
	'type' => 'object',
	'subtype' => 'album',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => $vars['entity']->num_display,
	'full_view' => false,
	'pagination' => false,
);
echo elgg_list_entities($options);
