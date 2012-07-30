<?php
/**
 * Delete album or image
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$guid = (int) get_input('guid');
$entity = get_entity($guid);
if (!$entity) {
	// unable to get Elgg entity
	register_error(elgg_echo("tidypics:deletefailed"));
	forward(REFERER);
}

if (!$entity->canEdit()) {
	// user doesn't have permissions
	register_error(elgg_echo("tidypics:deletefailed"));
	forward(REFERER);
}

$container = $entity->getContainerEntity();

$subtype = $entity->getSubtype();
switch ($subtype) {
	case 'album':
		if (elgg_instanceof($container, 'user')) {
			$forward_url = "photos/owner/$container->username";
		} else {
			$forward_url = "photos/group/$container->guid/all";
		}
		break;
	case 'image':
		$forward_url = $container->getURL();
		break;
	default:
		forward(REFERER);
		break;
}

if ($entity->delete()) {
	system_message(elgg_echo("tidypics:deleted"));
} else {
	register_error(elgg_echo("tidypics:deletefailed"));
}

forward($forward_url);
