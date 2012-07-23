<?php
/**
 * Admin area to view and delete cancellation requests.
 *
 */

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

$options = array(
	'type' => 'object',
	'subtype' => 'cancel_account_request',
	'owner_guid' => $userguid,
	'count' => true
);

$count_cancellation_requests = elgg_get_entities($options);


if (!$count_cancellation_requests) {

	echo autop(elgg_echo('cms_cancel_account:admin:no_requests'));
	return TRUE;
}

$options['count']  = FALSE;

$cancellation_requests = elgg_get_entities($options);

// setup pagination
$pagination = elgg_view('navigation/pagination',array(
	'baseurl' => 'admin/users/cancellations',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

$bulk_actions_checkbox = '<label><input type="checkbox" id="cms_cancel_account-checkall" />'
	. elgg_echo('cms_cancel_account:check_all') . '</label>';


$delete = elgg_view('output/url', array(
	'href' => 'action/cms_cancel_account/delete/',
	'text' => elgg_echo('cms_cancel_account:admin:delete'),
	'title' => elgg_echo('cms_cancel_account:confirm_delete_checked'),
	'class' => 'cms_cancel_account-submit',
	'is_action' => true,
	'is_trusted' => true,
));

$bulk_actions = <<<___END
	<ul class="elgg-menu elgg-menu-general elgg-menu-hz float-alt">
		<li>$delete</li>
	</ul>

	$bulk_actions_checkbox
___END;

if (is_array($cancellation_requests) && count($cancellation_requests) > 0) {
	$html = '<ul class="elgg-list elgg-list-distinct">';
	foreach ($cancellation_requests as $request) {
		$userguid = $request->owner_guid;
		$user = get_user($userguid);
		$reason = $request->reason;
		$html .= "<li id=\"cancellation_request-{$user->guid}\" class=\"elgg-item cms_cancel_account-request-user-item\">";		
		$html .= elgg_view('cms_cancel_account/cancellation_request', array('user' => $user, 'reason' => $reason));
		$html .= '</li>';
	}
	$html .= '</ul>';
}


echo <<<___END
<div class="elgg-module elgg-module-inline cms_cancel_account-module">
	<div class="elgg-head">
		$bulk_actions
	</div>
	<div class="elgg-body">
		$html
	</div>
</div>
___END;

if ($count > 5) {
	echo $bulk_actions;
}

echo $pagination;
