<?php
/**
 * Edit an image
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) get_input('guid');

if (!$entity = get_entity($guid)) {
	// @todo either deleted or do not have access
	forward('photos/all');
}

if (!$entity->canEdit()) {
	// @todo cannot change it
	forward($entity->getContainerEntity()->getURL());
}

$album = $entity->getContainerEntity();
if (!$album) {

}

elgg_set_page_owner_guid($album->getContainerGUID());
$owner = elgg_get_page_owner_entity();

gatekeeper();
group_gatekeeper();

$title = elgg_echo('image:edit');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), "photos/all");
if (elgg_instanceof($owner, 'user')) {
	elgg_push_breadcrumb($owner->name, "photos/owner/$owner->username");
} else {
	elgg_push_breadcrumb($owner->name, "photos/group/$owner->guid/all");
}
elgg_push_breadcrumb($album->getTitle(), $album->getURL());
elgg_push_breadcrumb($entity->getTitle(), $entity->getURL());
elgg_push_breadcrumb($title);

$vars = tidypics_prepare_form_vars($entity);
$content = elgg_view_form('photos/image/save', array('method' => 'post'), $vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
