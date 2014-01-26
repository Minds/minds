<?php
/**
 * Delete an invitation to join a group.
 *
 * @package ElggGroups
 */

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = get_input('group_guid');

$user = get_entity($user_guid,'user');

// invisible groups require overriding access to delete invite
$old_access = elgg_set_ignore_access(true);
$group = get_entity($group_guid,'group');
elgg_set_ignore_access($old_access);

// If join request made
if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
	remove_entity_relationship($group->guid, 'invited', $user->guid);
	system_message(elgg_echo("groups:invitekilled"));
}

forward(REFERER);
