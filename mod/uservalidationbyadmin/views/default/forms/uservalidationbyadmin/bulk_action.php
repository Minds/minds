<?php
/**
 * Admin area to view, validate, resend validation email, or delete unvalidated users.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail.Administration
 */

$limit = get_input('limit', 10);
$offset = get_input('offset', "");

$db = new Minds\Core\Data\Call('entities_by_time');
$guids = $db->getRow('user:unvalidated', array('offset'=>$offset, 'limit'=>$limit));		
$count =  $db->countRow('user:unvalidated');

if (!$count) {
	echo elgg_autop(elgg_echo('uservalidationbyadmin:admin:no_unvalidated_users'));
	return TRUE;
}

$options['count']  = FALSE;

$users = elgg_get_entities(array('type'=>'user','guids'=>$guids));

// setup pagination
$pagination = elgg_view('navigation/pagination',array(
	'base_url' => 'admin/users/unvalidated',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

$bulk_actions_checkbox = '<label><input type="checkbox" id="uservalidationbyemail-checkall" />'
	. elgg_echo('uservalidationbyemail:check_all') . '</label>';

$validate = elgg_view('output/url', array(
	'href' => 'action/uservalidationbyadmin/validate/',
	'text' => elgg_echo('uservalidationbyadmin:admin:validate'),
	'title' => elgg_echo('uservalidationbyadmin:confirm_validate_checked'),
	'class' => 'uservalidationbyemail-submit',
	'is_action' => true,
	'is_trusted' => true,
));


$delete = elgg_view('output/url', array(
	'href' => 'action/uservalidationbyadmin/delete/',
	'text' => elgg_echo('uservalidationbyadmin:admin:delete'),
	'title' => elgg_echo('uservalidationbyadmin:confirm_delete_checked'),
	'class' => 'uservalidationbyadmin-submit',
	'is_action' => true,
	'is_trusted' => true,
));

$bulk_actions = <<<___END
	<ul class="elgg-menu elgg-menu-general elgg-menu-hz float-alt">
		<li>$validate</li><li>$delete</li>
	</ul>

	$bulk_actions_checkbox
___END;

if (is_array($users) && count($users) > 0) {
	$html = '<ul class="elgg-list elgg-list-distinct">';
	foreach ($users as $user) {
		$html .= "<li id=\"unvalidated-user-{$user->guid}\" class=\"elgg-item uservalidationbyadmin-unvalidated-user-item\">";
		$html .= elgg_view('uservalidationbyadmin/unvalidated_user', array('user' => $user));
		$html .= '</li>';
	}
	$html .= '</ul>';
}

echo <<<___END
<div class="elgg-module elgg-module-inline uservalidation-module">
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
 
