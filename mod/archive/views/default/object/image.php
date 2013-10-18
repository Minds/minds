<?php
/**
 * Image view
 *
 * @uses $vars['entity'] TidypicsImage
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */


$full_view = elgg_extract('full_view', $vars, false);
$list_type = elgg_extract('list_type', $vars);

if ($full_view) {
	echo elgg_view('object/image/full', $vars);
} else {
	if($list_type == 'gallery'){
		echo elgg_view('object/image/gallery', $vars);
	} else {
		echo elgg_view('object/image/summary', $vars);
	}
}
