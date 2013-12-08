<?php
/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ggouv/elgg-deck_river
 *
 * View for facebook_account object
 *
 * @uses $vars['entity'] The account to display
 * @uses $vars['view_type'] View type. Default full. in_network_box or in_column_settings.
 * @uses $vars['pinned'] Whether the account is pinned. Default to false.
 **/


$account = elgg_extract('entity', $vars);
$view = elgg_extract('view_type', $vars, false);
$pinned = elgg_extract('pinned', $vars, false);
$position = elgg_extract('position', $vars, false);

$user_guid = elgg_get_logged_in_user_guid();

$avatar = elgg_view('output/img', array(
	'src' => $account->icon ? $account->icon : 'https://graph.facebook.com/' . $account->user_id . '/picture', // facebook group use icon, else this is a user account
	'alt' => $account->name,
	'class' => 'float',
));

if ($account->icon) { // this is a group
	$link = 'groups/' . $account->user_id;
	$type = 'group';
} else if ($account->parent_id) { // this is a page
	$link = 'pages/' . $account->name . '/' . $account->user_id;
	$type = 'page';
} else { // this is a facebook user
	$link = $account->username;
	$type = 'user';
}

if ($view === 'in_network_box') {
	if ($pinned) {
		$pinned = ' pinned';
		$input_name = 'networks[]';
	} else {
		$input_name = '_networks[]';
	}


	$info = '<div class="elgg-river-summary"><span class="facebook-' . $type . '-info-popup info-popup" title="' . $account->user_id . '">' . $account->name . '</span>';
	$info .= '<br/><span class="elgg-river-timestamp">';
	$info .= elgg_view('output/url', array(
		'href' => 'http://facebook.com/' . $link,
		'text' => 'http://facebook.com/' . $link,
		'target' => '_blank',
		'rel' => 'nofollow'
	));
	$info .= '</span></div>';

	$pin_tooltip = htmlspecialchars(elgg_echo('deck-river:network:pin'));

	$output = <<<HTML
<div class="net-profile float mlm facebook$pinned" data-position="$position">
	<input type="hidden" value="{$account->getGUID()}" name="$input_name" data-network="facebook" data-scrap>
	<ul>
		<span class="elgg-icon elgg-icon-delete pas hidden"></span>
		<div class="elgg-module-popup hidden">
			<div class="triangle"></div>
			<div class="pin float-alt">
			<span class="elgg-icon elgg-icon-push-pin tooltip w link" title="$pin_tooltip"></span>
			</div>
			$info
		</div>
	</ul>
	$avatar
	<span class="network gwf link">F</span>
</div>
HTML;

	echo $output;

} else if ($view === 'in_column_settings') {

} else { // full view in applications user settings page

	$owner = $account->getOwnerEntity();

	$owner_link = elgg_view('output/url', array(
		'href' => "profile/$owner->username",
		'text' => $owner->name,
		'is_trusted' => true,
	));
	$author_text = elgg_echo('deck_river:account:createdby', array('Facebook', elgg_get_site_entity()->name, $owner_link));
	$date = elgg_view_friendly_time($account->time_created);

	if ($owner->getGUID() == $user_guid) {
		if (get_readable_access_level($account->access_id) == 'shared_network_acl') {
			$access = '<span title="' . elgg_echo('access:help') . '" class="elgg-access elgg-access-private tooltips s">' . elgg_echo('deck_river:collection:shared') . '</span>';
		} else {
			$access = elgg_view('output/access', array('entity' => $account));
		}
		$delete = elgg_view('output/url', array(
			'href' => "action/deck_river/network/delete?guid={$account->getGUID()}",
			'text' => elgg_view_icon('delete'),
			'title' => elgg_echo('delete:this'),
			'class' => 'tooltip s t elgg-requires-confirmation',
			'rel' => elgg_echo('deck_river:account:deleteconfirm'),
			'is_action' => true,
		));
	}

	$subtitle = "$author_text $date";

	$link = elgg_view('output/url', array(
		'href' => 'http://facebook.com/' . $link,
		'text' => 'http://facebook.com/' . $link,
		'class' => 'external',
		'target' => '_blank'
	));

	if (!$account->icon && !$account->parent_id) {
		if ($owner->getGUID() == $user_guid) {
			$block = elgg_view('output/url', array(
				'href' => '#',
				'onclick' => "elgg.deck_river.getFBGroups('{$account->user_id}', '{$account->oauth_token}', '{$account->getGUID()}');",
				'text' => elgg_echo('deck_river:facebook:account:add_groups'),
				'rel' => 'nofollow'
			));
			$block .= '<br>' . elgg_view('output/url', array(
				'href' => '#',
				'onclick' => "elgg.deck_river.getFBPages('{$account->user_id}', '{$account->oauth_token}', '{$account->getGUID()}');",
				'text' => elgg_echo('deck_river:facebook:account:add_pages'),
				'rel' => 'nofollow'
			));
		}
	} else {
		$block = elgg_echo('deck_river:facebook:account:' . ($account->icon ? 'group' : 'page'), array($account->username));
	}

	$block .= elgg_view('deck_river/account_block', array('account' => $account));

	echo <<<HTML
<div class="elgg-content row-fluid">
	<div class="span8">
		<div class="elgg-image-block clearfix">
			<div class="elgg-image">
				<span title="{$account->user_id}" class="facebook-user-info-popup info-popup">$avatar</span>
			</div>
			<div class="elgg-body">
				<ul class="elgg-menu elgg-menu-entity elgg-menu-hz elgg-menu-entity-default">
					<li class="elgg-menu-item-access">$access</li>
					<li class="elgg-menu-item-delete">$delete</li>
				</ul>
				<h3><span class="facebook-{$type}-info-popup info-popup" title="{$account->user_id}">{$account->name}</span></h3>
				$link
				<div class="elgg-subtext">$subtitle</div>
			</div>
		</div>
	</div>
	<div class="elgg-heading-basic pam span4">
		$block
	</div>
</div>
HTML;

}

