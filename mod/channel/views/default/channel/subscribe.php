<?php

$user = $vars['entity'];

if($user->guid == elgg_get_logged_in_user_guid()){
	return false;
}
$tooltip = 'subscribe';
if (elgg_is_logged_in()) {
		if (elgg_get_logged_in_user_guid() != $user->guid) {
			if ($user->isFriend()) {
				$text = elgg_echo('friend:remove');
				$tooltip = 'unsubscribe';
				$href = elgg_add_action_tokens_to_url("action/friends/remove?friend={$user->guid}");
			} else {
				$href = elgg_add_action_tokens_to_url("action/friends/add?friend={$user->guid}");
				$text = elgg_echo('friend:add');
			}
		}
} else {
	$href = elgg_add_action_tokens_to_url("action/friends/add?friend={$user->guid}");
	$text = elgg_echo('friend:add');
}

?>
<div class="subscribe-button tooltip n" title="click to <?php echo $tooltip;?>">
	<a href="<?php echo $href;?>">
		<span class="text">
			<?php echo $text;?>
		</span>
		<span class="subscribers-count">
			<?php echo $user->getSubscribersCount();?>
		</span>
	</a>
</div>
