<?php
/**
 * Album view
 * 
 * @uses $vars['entity'] TidypicsAlbum
 * 
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$album = elgg_extract('entity', $vars);
$full_view = elgg_extract('full_view', $vars, false);
$archive_view = elgg_extract('archive_view', $vars, false);

if ($full_view) {
	echo elgg_view('object/album/full', $vars);
} else {
	if (elgg_in_context('widgets')) {
		echo elgg_view('object/album/list', $vars);
	} elseif($archive_view) {
		echo elgg_view('object/album/archive', $vars);
	} else {
		echo elgg_view('object/album/gallery', $vars);
	}
}

if ($album->getContainerEntity()->canWriteToContainer()) {
	if($full_view){
		elgg_register_menu_item('title', array(
			'name' => 'upload',
			'href' => 'archive/upload/album/' . $album->getGUID(),
			'text' => elgg_echo('images:upload'),
			'link_class' => 'elgg-button elgg-button-action',
		));
	}
}