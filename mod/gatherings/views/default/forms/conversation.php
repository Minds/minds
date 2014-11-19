<?php

$user = $vars['user'];
$encrypted = $vars['encrypted'];

?>
<div class="conversation">
	<div class="messages">
		
	</div>
	<div class="input">
		<textarea name="message"></textarea>
		<input type="hidden" name="user_guid" value="<?= $user->guid ?>"/>
		<input type="submit" value="Send"/>
	</div>
</div>




