<?php
/**
 * View an image
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

group_gatekeeper();

// get the photo entity
$photo_guid = (int) get_input('guid');
$photo = get_entity($photo_guid);
if (!$photo) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}

$photo->addView();

if (elgg_get_plugin_setting('tagging', 'tidypics')) {
	elgg_load_js('tidypics:tagging');
	elgg_load_js('jquery.imgareaselect');
}

// set page owner based on owner of photo album
$album = $photo->getContainerEntity();
if ($album) {
	elgg_set_page_owner_guid($album->getContainerGUID());
}
$owner = elgg_get_page_owner_entity();

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/all');
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb($owner->name, "photos/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($owner->name, "photos/owner/$owner->username");
}
elgg_push_breadcrumb($album->getTitle(), $album->getURL());
elgg_push_breadcrumb($photo->getTitle());

if (elgg_get_plugin_setting('download_link', 'tidypics')) {
	// add download button to title menu
	elgg_register_menu_item('title', array(
		'name' => 'download',
		'href' => "photos/download/$photo_guid",
		'text' => elgg_echo('image:download'),
		'link_class' => 'elgg-button elgg-button-action',
	));
}

$content = elgg_view_entity($photo, array('full_view' => true));

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $photo->getTitle(),
	'sidebar' => elgg_view('photos/sidebar', array(
		'page' => 'view',
		'image' => $photo,
	)),
));

echo elgg_view_page($photo->getTitle(), $body);
