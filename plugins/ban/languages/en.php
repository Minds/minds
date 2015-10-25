<?php
/**
 * Banned users English language file
 */

$english = array(
	'ban:profile_link' => 'Ban',
	'ban:admin_menu' => 'Banned users',
	'admin:users:ban' => 'Banning user',
	'ban:list:title' => 'Banned users',
	'ban:menu:ban' => 'Ban',
	'ban:menu:unban' => 'Unban',
	'admin:users:ban_list' => 'Banned users',

	'ban:reason' => 'Reason',
	'ban:length' => 'Length of time in hours (0 = forever)',
	'ban:notify' => 'Notify user',

	'ban:hourleft' => '%s hour left',
	'ban:hoursleft' => '%u hours left',

	'ban:forever' => 'Forever',
	'ban:none' => 'No banned users',

	'ban:add:success' => "Banned %s",
	'ban:add:failure' => 'Failed to ban this user',

	'ban:subject' => "You have been banned from %s",
	'ban:body' => "You have been banned for the following reason: \n\n %s \n\n The ban will last %u hours.",
	'ban:body:forever' => "You have been banned forever for the following reason: \n\n %s.",
);

add_translation("en", $english);