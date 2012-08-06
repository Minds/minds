<?php
/**
 * Upload images
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

gatekeeper();

$album_guid = (int) get_input('guid');
if (!$album_guid) {
	// @todo
	forward();
}

$album = get_entity($album_guid);
if (!$album) {
	// @todo
	// throw warning and forward to previous page
	forward(REFERER);
}

if (!$album->getContainerEntity()->canWriteToContainer()) {
	// @todo have to be able to edit album to upload photos
	forward(REFERER);
}

// set page owner based on container (user or group)
elgg_set_page_owner_guid($album->getContainerGUID());
$owner = elgg_get_page_owner_entity();

$title = elgg_echo('album:addpix');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), "photos/all");
elgg_push_breadcrumb($owner->name, "photos/owner/$owner->username");
elgg_push_breadcrumb($album->getTitle(), $album->getURL());
elgg_push_breadcrumb(elgg_echo('album:addpix'));

// load javascript dependences
elgg_load_js('jquery-tmpl');
elgg_load_js('jquery-load-image');
elgg_load_js('jquery-canvas-to-blob');
elgg_load_js('jquery-fileupload');
elgg_load_js('jquery-fileupload-ui');
elgg_load_js('tidypics:upload');

$form_vars = array(
	'id' => 'fileupload',
	'action' => 'action/photos/image/upload',
	'enctype' => 'multipart/form-data',
);

$content = elgg_view_form('photos/basic_upload', $form_vars, array('entity' => $album));

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'sidebar' => elgg_view('photos/sidebar', array('page' => 'upload')),
));

echo elgg_view_page($title, $body);
