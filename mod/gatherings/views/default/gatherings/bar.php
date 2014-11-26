<?php 

/**
 * Nasty little hack here, but just for a demo!
 */
if(!elgg_is_logged_in()){
	return true;
}
//get online users
//$users = find_active_users(600, 0);
?>
<div class="minds-live-chat">
	<audio id="sound">
	<source src="<?php echo elgg_get_site_url();?>mod/gatherings/sounds/chatter.wav" type="audio/wav"/> 
	<source src="<?php echo elgg_get_site_url();?>mod/gatherings/sounds/blup.mp3" type="audio/mp3"/>
	</audio>
	<audio id="tone" loop>
		<source src="https://freesound.org/data/previews/39/39061_402511-lq.ogg" type="audio/ogg"/> 
		<source src="https://freesound.org/data/previews/39/39061_402511-lq.mp3" type="audio/mp3"/>
	</audio>
	<audio id="ringer" loop>
		<source src="https://freesound.org/data/previews/77/77723_91595-lq.ogg" type="audio/wav"/> 
		<source src="https://freesound.org/data/previews/77/77723_91595-lq.mp3" type="audio/mp3"/>
	</audio>
	<div class="minds-live-chat-userlist">
		<ul>
			<li class="userlist"> <h3> <span class="entypo"> &#59160; </span> Chat <span class="sound sound-on entypo" title="turn sound on">&#59412;</span> <span class="sound sound-off entypo" title="Mute sound">&#59411;</span> </h3> 
				<ul>
					<span class="chat-loading">
						Please wait... Connecting...
					</span>
				</ul>
			</li>
		</ul>
	</div>
</div>
