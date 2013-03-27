<?php
/**
 * This displays the photos that belong to an album
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

group_gatekeeper();

// get the album entity
$album_guid = (int) get_input('guid');
$album = get_entity($album_guid);
if (!$album) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}

elgg_set_page_owner_guid($album->getContainerGUID());
$owner = elgg_get_page_owner_entity();

$title = elgg_echo($album->getTitle());

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/all');
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb($owner->name, "photos/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($owner->name, "photos/owner/$owner->username");
}
elgg_push_breadcrumb($album->getTitle());

$content = elgg_view_entity($album, array('full_view' => true));

if ($album->getContainerEntity()->canWriteToContainer()) {
	elgg_register_menu_item('title', array(
			'name' => 'upload',
			'href' => 'photos/upload/' . $album->getGUID(),
			'text' => elgg_echo('images:upload'),
			'link_class' => 'elgg-button elgg-button-action',
	));
}

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $album->getTitle(),
	'sidebar' => elgg_view('tidypics/sidebar', array('page' => 'album')),
));

echo elgg_view_page($title, $body);
