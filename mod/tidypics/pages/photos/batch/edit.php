<?php
/**
 * Edit the image information for a batch of images
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

gatekeeper();

$guid = (int) get_input('guid');

if (!$batch = get_entity($guid)) {
	// @todo either deleted or do not have access
	forward('photos/all');
}

if (!$batch->canEdit()) {
	// @todo cannot change it
	forward('photos/all');
}

$album = $batch->getContainerEntity();

elgg_set_page_owner_guid($batch->getContainerEntity()->getContainerGUID());
$owner = elgg_get_page_owner_entity();

$title = elgg_echo('tidypics:editprops');

elgg_push_breadcrumb(elgg_echo('photos'), "photos/all");
elgg_push_breadcrumb($owner->name, "photos/owner/$owner->username");
elgg_push_breadcrumb($album->getTitle(), $album->getURL());
elgg_push_breadcrumb($title);

$content = elgg_view_form('photos/batch/edit', array(), array('batch' => $batch));

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => elgg_echo('tidypics:editprops'),
	'sidebar' => elgg_view('tidypics/sidebar', array('page' => 'album')),
));

echo elgg_view_page($title, $body);
