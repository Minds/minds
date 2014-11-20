<?php

$messages = $vars['messages'];
$conversation = $vars['conversation'];

?>

<div class="conversation-wrapper">
	<div class="conversation-messages">
		<?php foreach($messages as $message){
			echo elgg_view_entity($message); 
		} ?>
	</div>
</div>
