<?php

$messages = $vars['messages'];
$conversation = $vars['conversation'];

?>

<div class="conversation-wrapper">

	<script>
		var conversation_participants = [];
		<?php foreach($conversation->participants as $participant):
		// if($participant == elgg_get_logged_in_user_guid()) continue;
		?>
		conversation_participants.push("<?=$participant?>");
		var obj_template_<?=$participant?> = <?= json_encode(elgg_view_entity(new minds\plugin\gatherings\entities\message(array('owner_guid'=>$participant)))); ?>;
		<?php endforeach; ?>
		
		var obj_template = <?= json_encode(elgg_view_entity(new minds\plugin\gatherings\entities\message())); ?>
	</script>

	<ul class="conversation-messages">
		
		<?php if(count($messages) == 30): ?>
		<li class="clearfix load-more">
			<p>Click to load earlier message.</p>
		</li>
		<?php endif; ?>
		
		<?php foreach($messages as $message){ ?>
			<li class="clearfix" id="<?= $message->guid ?>">
				<?= elgg_view_entity($message) ?>
			</li>
		<?php } ?>
	
	</ul>
</div>
