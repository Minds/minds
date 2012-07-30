<?php
/**
 * Album RSS view
 *
 * @uses $vars['entity'] TidypicsAlbum
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$full_view = elgg_extract('full_view', $vars, false);

if ($full_view) {
	echo elgg_view('object/album/full', $vars);
} else {
	echo elgg_view('object/album/summary', $vars);
}
