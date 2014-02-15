<?php 

/**
 * Nasty little hack here, but just for a demo!
 */
if(!elgg_is_logged_in()){
	return true;
}
//get online users
$users = find_active_users(600, 0);
?>
<div class="minds-live-chat">
	<audio id="sound">
	<source src="<?php echo elgg_get_site_url();?>mod/chat/sounds/chatter.wav" type="audio/wav"/> 
	<source src="<?php echo elgg_get_site_url();?>mod/chat/sounds/blup.mp3" type="audio/mp3"/>
	</audio>
	<div class="minds-live-chat-userlist">
		<ul>
			<li class="userlist"> <h3> <span class="entypo"> &#59160; </span> Chat <h3/>
				<ul>
				<?php 
					foreach($users as $user){
						if($user->guid != elgg_get_logged_in_user_guid()){
							 echo "<li class='user' id='$user->guid'> <h3>$user->name</h3></li>";
						}
					}
				?>
				</ul>
			</li>
			<?php 
/*				foreach($users as $user){
					if($user->guid != elgg_get_logged_in_user_guid()){
						echo "<li class='box' id='$user->guid'>
							<h3>$user->name</h3>
							<div class='messages'>
							</div>
							<div> <input type='text' class='elgg-input' /> </div></li>";
					}
				}*/
				?>
		</ul>
	</div>
</div>
