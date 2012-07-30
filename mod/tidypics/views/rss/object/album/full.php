<?php
/**
 * List photos in an album for RSS
 *
 * @uses $vars['entity'] TidypicsAlbum
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$limit = (int)get_input('limit', 20);

echo elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'image',
	'container_guid' => $vars['entity']->getGUID(),
	'limit' => $limit,
	'full_view' => false,
));
