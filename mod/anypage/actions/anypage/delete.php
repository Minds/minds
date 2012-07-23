<?php
/**
 * Delete a page
 */

$guid = get_input('guid');
$page = get_entity($guid);

if (elgg_instanceof($page, 'object', 'anypage') && $page->canEdit()) {
	if ($page->delete()) {
		system_message(elgg_echo("anypage:delete:success"));
		forward('admin/appearance/anypage');
	}
}

register_error(elgg_echo("anypage:delete:failed"));
forward(REFERER);
