<?php

$messages = $vars['messages'];
$conversation = $vars['conversation'];

?>

<div class="conversation-wrapper">
	<ul class="conversation-messages">
		
		<?php foreach($messages as $message){ ?>
			<li class="clearfix">
				<?= elgg_view_entity($message) ?>
			</li>
		<?php } ?>
	
	</ul>
</div>
