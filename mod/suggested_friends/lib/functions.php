<?php

/*
 * People usort callback
 */
function suggested_friends_sorter($a, $b){
	if ($a['priority'] == $b['priority']) {
		return 0;
	}
	return ($a['priority'] < $b['priority']) ? 1 : -1;
}

/**
 *
 * Returns array of people containing entity, mutuals (friends), groups (shared) and priority
 * @param Int $guid
 * @param Int $friends_limit
 * @param Int $groups_limit
 * @return Array
 */
function suggested_friends_get_people($guid, $friends_of_friends_limit = 3, $groups_members_limit = 3) {

	global $CONFIG;

	// retrieve all users friends
	$options = array(
		'type' => 'user',
		'relationship' => 'friend',
		'relationship_guid' => $guid,
		'wheres' => "u.banned = 'no'",
		'joins' => "INNER JOIN {$CONFIG->dbprefix}users_entity u USING (guid)",
		'order_by' => 'u.last_action DESC',
	    'limit' => 0,
	);
	
	$friends = elgg_get_entities_from_relationship($options);
    
	// generate a guids array
	$in = array($guid);
  if(is_array($friends) && count($friends) > 0){
    foreach ($friends as $friend) {
      $in[] = $friend->guid;
    }
  }

	$in = implode(',', $in);

	$people = array();

	/* seach by friends */
	if ($friends_of_friends_limit > 0) {
		foreach ($friends as $friend) {
			// retrieve friends of each friend (discarding the users friends)
			$fof = elgg_get_entities_from_relationship(array(
				'type' => 'user',
				'relationship' => 'friend',
				'relationship_guid' => $friend->guid,
				'wheres' => array(
					"e.guid NOT IN ($in)",
					"u.banned = 'no'"
				),
				'joins' => "INNER JOIN {$CONFIG->dbprefix}users_entity u USING (guid)",
				'order_by' => 'u.last_action DESC',
				'limit' => $friends_of_friends_limit
			));
			if (is_array($fof) && count($fof) > 0) {
				// populate $people
				foreach ($fof as $f) {
					if (isset($people[$f->guid])) {
						// if the current person is present in $people, increase the priority and attach the common friend entity
						$people[$f->guid]['mutuals'][] = $friend;
						++$people[$f->guid]['priority'];
					} else {
						$people[$f->guid] = array(
							'entity' => $f,
							'mutuals' => array($friend),
							'groups' => array(),
							'priority' => 0
						);
					}
				}
			}
		}
	}
	unset($friends);

	/* search by groups */
	if ($groups_members_limit > 0) {
		// retrieve ($groups_limit) user's groups
		$options = array(
			'type' => 'group',
			'relationship' => 'member',
			'relationship_guid' => $guid,
			'order_by' => 'time_created DESC',
			'limit' => 0
		);
		
		$groups = elgg_get_entities_from_relationship($options);

		if (is_array($groups) && count($groups) > 0) {
			foreach ($groups as $group) {
				// retrieve 3 members of each group (discarding the users friends)
				$members = elgg_get_entities_from_relationship(array(
					'type' => 'user',
					'relationship' => 'member',
					'relationship_guid' => $group->guid,
					'inverse_relationship' => TRUE,
					'wheres' => array(
						"e.guid NOT IN ($in)",
						"u.banned = 'no'"
					),
					'joins' => "INNER JOIN {$CONFIG->dbprefix}users_entity u USING (guid)",
					'order_by' => 'u.last_action DESC',
					'limit' => $groups_members_limit
				));
				if (is_array($members) && count($members) > 0) {
					// populate $people
					foreach ($members as $member) {
						if (isset($people[$member->guid])) {
							// if the current person is present in $people, increase the priority and attach the common group entity
							$people[$member->guid]['groups'][] = $group;
							++$people[$member->guid]['priority'];
						} else {
							$people[$member->guid] = array(
								'entity' => $member,
								'mutuals' => array(),
								'groups' => array($group),
								'priority' => 0
							);
						}
					}
				}
			}
		}
		unset($groups);
	}

	// sort by priority
	usort($people, 'suggested_friends_sorter');

	return $people;

}