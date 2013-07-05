<?php 

/**
 * Nasty little hack here, but just for a demo!
 */

//get online users
$users = find_active_users(600, 0);
?>
<div class="minds-live-chat">
	<div class="minds-live-chat-userlist">
		<span class="entypo">&#59160;</span>
		<ul>
			<?php 
				foreach($users as $user){
	//				if($user->guid != elgg_get_logged_in_user_guid()){
						echo "<li id='$user->guid'>
							<h3>$user->name</h3>
							<div class='messages'>
							</div>
							<div> <input type='text' class='elgg-input' /> </div></li>";
	//				}
				}
				?>
		</ul>
	</div>
</div>
