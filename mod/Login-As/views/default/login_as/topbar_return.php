<?php
/**
 * A topbar link to return to original user.
 *
 * @uses $vars['user_guid'] The GUID of the original user
 */

$original_user_guid = elgg_extract('user_guid', $vars);
$original_user = get_entity($original_user_guid);
if ($original_user) {
	$logged_in_user = elgg_get_logged_in_user_entity();
	$logged_in_user_icon = $logged_in_user->getIconURL('topbar');
	$original_user_icon = $original_user->getIconURL('topbar');

	echo <<<HTML
	<img class="elgg-border-plain" src="$logged_in_user_icon" />
	<span class="login-as-arrow">&rarr;</span>
	<img class="elgg-border-plain" src="$original_user_icon" />
HTML;
}
