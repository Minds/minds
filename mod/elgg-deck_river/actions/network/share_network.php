<?php
/**
 * Action for share a network with another person
 */

action_gatekeeper();

// don't filter since we strip and filter escapes some characters
$account_guid = (int) get_input('account');
$members = (array) get_input('members');

if (!$account_guid) {
	register_error(elgg_echo('deck_river:error:shared_to_user'));
} else {
	$account = get_entity($account_guid);

	if ($account->canEdit()) {

		if (empty($members)) {
			if ($account->access_id != ACCESS_PRIVATE) {
				delete_access_collection($account->access_id);
				$account->access_id = ACCESS_PRIVATE;
				$account->save();
			}
		} else {
			// check if account has acl collection, if not create it
			if ($account->access_id == ACCESS_PRIVATE) {
				$account->access_id = create_access_collection('shared_network_acl', $account->getOwnerGUID());
				$account->save();
			}
			update_access_collection($account->access_id, $members);
		}

		if (get_readable_access_level($account->access_id) == 'shared_network_acl') {
			$access = '<span title="' . elgg_echo('access:help') . '" class="elgg-access elgg-access-private tooltips s">' . elgg_echo('deck_river:collection:shared') . '</span>';
		} else {
			$access = elgg_view('output/access', array('entity' => $account));
		}

		echo json_encode(array(
			'account_block' => elgg_view('deck_river/account_block', array('account' => $account)),
			'access' => $access
		));

	} else {
		register_error(elgg_echo('deck_river:error:shared_to_user'));
	}
}