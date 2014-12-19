<?php
$conversation = isset($vars['conversation'])  ? $vars['conversation'] : NULL;
$conversations = $vars['conversations'];
?>

<form action="<?= elgg_get_site_url() ?>gatherings/conversation/new" class="conversation-engage">
	
	<?= elgg_view('input/autocomplete', array('data-type'=>'user', 'placeholder'=>'Search users...', 'class'=>'user-lookup', 'name'=>'u', 'value'=>get_input('referrer'))) ?>
	
	<input type="submit" value="Start conversation" class="elgg-button elgg-button-action"/>

</form>
<ul class="conversations-list">
	<?php foreach($conversations as $user):
		if($user->guid == elgg_get_logged_in_user_entity()->guid)
			continue;
	 ?>
		<li class="<?= ($conversation && in_array($user->guid, $conversation->participants)) ? 'active' : '' ?>">
			<div class="icon">
				<?= elgg_view_entity_icon($user, 'small'); ?>
			</div>
			<a href="<?= elgg_get_site_url() ?>gatherings/conversation/<?=$user->username?>">
				<h3><?= $user->name ?></h3>
				
				<?php if($user->unread): ?>
				<div class="unread">.</div>
				<?php endif; ?>
				
				<span class="ts"><?= elgg_view_friendly_time($user->last_msg ?: time()) ?></span>
				
			</a>
		</li>
	<?php endforeach; ?>
</ul>
<a href="<?= elgg_get_site_url() ?>gatherings/configure" style="display:block; padding:16px; font-weight:bold;">Configuration & Settings</a>
