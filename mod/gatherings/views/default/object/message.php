<?php

$message = $vars['entity'];
$owner = $message->getOwnerEntity();
?>

<div class="message">
	<?= elgg_view_entity_icon($owner) ?>
	<?= $message->decryptMessage() ?>
	<span class="time">
		<?= elgg_view_friendly_time($message->time_created) ?>
	</span>
</div>
