<?php
/**
 * Layout of the groups profile page
 *
 * @uses $vars['entity']
 */

if (group_gatekeeper(false)) {
	echo elgg_view('groups/profile/activity', $vars);
} else {
	echo elgg_view('groups/profile/closed_membership');
}
