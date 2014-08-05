<?php

$account = elgg_extract('account', $vars);

$user_guid = elgg_get_logged_in_user_guid();

if ($account->access_id != ACCESS_PRIVATE) {
	$sharedWith = get_members_of_access_collection($account->access_id);
	foreach ($sharedWith as $user) {
		$body .= elgg_view_entity_icon($user, 'tiny');
	}
}

echo '<div class="shared_users_block">';

if (!$body) {

	if ($account->canEdit() && $account->getOwnerGUID() == $user_guid) {
		echo elgg_view('output/url', array(
			'href' => '#',
			'text' => elgg_echo('deck_river:account:share:add'),
			'is_trusted' => true,
			'class' => 'share-account',
			'data-account_guid' => $account->getGUID(),
		));
	}

} else {

	if ($account->canEdit() && $account->getOwnerGUID() == $user_guid) {
		$add_link = elgg_view('output/url', array(
			'href' => '#',
			'text' => '+',
			'is_trusted' => true,
			'class' => 'share-account aside-plus gwf tooltip sw',
			'data-account_guid' => $account->getGUID(),
			//'title' => elgg_echo('deck_river:account:share:add')
		));
	}
	echo elgg_view_module('aside mbn', elgg_echo('deck_river:account:shared_with') . $add_link, $body);
}

echo '</div>';
