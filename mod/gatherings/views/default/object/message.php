<?php

$message = $vars['entity'];
$owner = $message->getOwnerEntity();
?>

<div class="message">
		
	<div class="actions">
		<a class="entypo delete">&#10062</a>
	</div>

	<div class="icon" style="float:left">
		<?= elgg_view_entity_icon($owner, 'small') ?>
	</div>
	<div class="clearfix message-content">
		<?= minds_filter(htmlspecialchars($message->decryptMessage(), ENT_QUOTES, 'UTF-8')) ?>
		<span class="time">
			<?= elgg_view_friendly_time($message->time_created) ?>
		</span>
	</div>

</div>
