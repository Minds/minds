<?php
	/**
	 * Beechat
	 * 
	 * @package beechat
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Beechannels <contact@beechannels.com>
	 * @copyright Beechannels 2007-2010
	 * @link http://beechannels.com/
	 */

	header('Content-type: application/json');
	gatekeeper();
	error_log("beechat:get_statuses");
	$usernames = get_input('beechat_roster_items_usernames');
	if (!empty($usernames))
	{
		$iconSize = 'small';
		$rosterItemsUsernames = explode(',', $usernames);
		$userFriendsEntities = $_SESSION['user']->getFriends('', 1000000000, 0);
		
		$res = array();
		foreach ($rosterItemsUsernames as $value)
		{
			foreach ($userFriendsEntities as $friend)
			{
				if (strtolower($friend->username) == strtolower($value))
				{
					$status = get_entities_from_metadata("state", "current", "object", "status", $friend->get('guid'));
					$res[$value] = ($status != false) ? $status[0]->description : '';
					break;
				}
			}
		}
		echo json_encode($res);
	}
	else
		echo json_encode(null);

	exit();

?>
