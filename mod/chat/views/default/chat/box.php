<?php
$user_guid = elgg_extract('user_guid', $vars, get_input('user_guid'));

if(!$user_guid){
 	return false;
}

$user = get_entity($user_guid);
//get last 10 messages, check sessions first incase we are 
?>	
<li class="box toggled" id="<?php echo $user->guid; ?>">
	<h3><?php echo $user->name; ?></h3>
        <div class="messages">
		<?php if(get_input('message')){ echo '<span class="message">' . $user->name . ': ' . get_input('message');} ?></div>
        <div> <input type="text" class="elgg-input" /> </div>
</li>
