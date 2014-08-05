<?php

gatekeeper();

$account_guid = get_input('account_guid', 'false');

$user_guid = elgg_get_logged_in_user_guid();

if ($account_guid) {

	if ($account = get_entity($account_guid)) {

		if ($account->canEdit()) {

			echo '<label>' . elgg_echo('deck_river:account:share:select') . '</label><br>';

			if ($account->access_id != ACCESS_PRIVATE) {
				$users = get_members_of_access_collection($account->access_id, true);
			}

			echo elgg_view('input/userpicker', array(
				'name' => 'shared_with',
				'value' => $users
			));

			echo elgg_view('input/hidden', array(
				'name' => 'account',
				'value' => $account_guid
			));

			echo '<div class="mtm">' . elgg_view('input/securitytoken');
			echo elgg_view('input/button', array(
				'value' => elgg_echo('save'),
				'class' => 'elgg-button-submit'
			)) . '</div>';

			echo elgg_view('output/longtext', array(
				'value' => elgg_echo('deck_river:account:share:warning')
			));
		} else {
			echo elgg_echo('deck_river:ajax:erreur');
		}

	}

}



