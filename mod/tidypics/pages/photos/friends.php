<?php
/**
 * List all the albums of someone's friends
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('photos'), "photos/all");
elgg_push_breadcrumb($owner->name, "photos/friends/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo('album:friends');


$num_albums = 16;

set_input('list_type', 'gallery');
$content = list_user_friends_objects($owner->guid, 'album', $num_albums, false);
if (!$content) {
	$content = elgg_echo('tidypics:none');
}

elgg_register_title_button();

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
