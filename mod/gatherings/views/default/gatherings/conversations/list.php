<?php

$conversations = $vars['conversations'];
?>

<form action="<?= elgg_get_site_url() ?>gatherings/conversation/new" class="conversation-engage">
	
	<?= elgg_view('input/autocomplete', array('data-type'=>'user', 'placeholder'=>'Search users...', 'class'=>'user-lookup', 'name'=>'u', 'value'=>get_input('referrer'))) ?>
	
	<input type="submit" value="Start conversation" class="elgg-button elgg-button-action"/>

</form>
<ul class="conversations-list">
	<?php foreach($conversations as $user): ?>
		<li>
			<div class="icon">
				<?= elgg_view_entity_icon($user, 'small'); ?>
			</div>
			<a href="<?= elgg_get_site_url() ?>gatherings/conversation/<?=$user->username?>">
				<h3><?= $user->name ?></h3>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
