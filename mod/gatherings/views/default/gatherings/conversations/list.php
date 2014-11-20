<?php

$conversations = $vars['conversations'];
?>

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
