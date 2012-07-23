<?php
/**
 * Formats and lists a cancellation request
 *
 */

$user = elgg_extract('user', $vars);
$reason = elgg_extract('reason', $vars);

$checkbox = elgg_view('input/checkbox', array(
	'name' => 'user_guids[]',
	'value' => $user->guid,
	'default' => false,
));


$reason = elgg_echo('cms_cancel_account:reason') . $reason;

$delete = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('cms_cancel_account:confirm_delete', array($user->username)),
	'href' => "action/cms_cancel_account/delete/?user_guids[]=$user->guid",
	'text' => elgg_echo('cms_cancel_account:admin:delete')
));
$menu = 'test';
$block = <<<___END
	<label>$user->username: "$user->name" &lt;$user->email&gt;</label>
	<div class="cms_cancel_account-request-user-details">
		$reason
	</div>	
___END;

$menu = <<<__END
	<ul class="elgg-menu elgg-menu-general elgg-menu-hz float-alt">
		<li>$delete</li>
	</ul>
__END;

echo elgg_view_image_block($checkbox, $block, array('image_alt' => $menu));
