<?php
/**
 * Create new album page
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$owner = elgg_get_logged_in_user_entity();

gatekeeper();
group_gatekeeper();

$title = elgg_echo('photos:add');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), "archive/all");
if (elgg_instanceof($owner, 'user')) {
	elgg_push_breadcrumb($owner->name, "archive/$owner->username");
} else {
	elgg_push_breadcrumb($owner->name, "archive/group/$owner->guid/all");
}
elgg_push_breadcrumb($title);

$vars = tidypics_prepare_form_vars();
$content = elgg_view_form('archive/add_album', array('method' => 'post'), $vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
