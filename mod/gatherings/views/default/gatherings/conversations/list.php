<?php

$conversations = $vars['conversations'];
?>

<ul class="conversations-list">
	<?php foreach($conversations as $user): ?>
		<li>
			<div class="icon">
				<?= elgg_view_entity_icon($user, 'small'); ?>
			</div>
			<h3><?= $user->name ?></h3>
		</li>
	<?php endforeach; ?>
</ul>
