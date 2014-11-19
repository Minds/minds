<?php
?>

<form action="gatherings/conversation/new">
	
	<?= elgg_view('input/autocomplete', array('data-type'=>'user', 'placeholder'=>'Who do you want to chat with?', 'class'=>'user-lookup', 'name'=>'username', 'value'=>get_input('referrer'))) ?>
	
	<input type="submit" value="Start conversation"/>

</form>