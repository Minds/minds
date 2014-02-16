<?php
/**
 * 
 */
elgg_load_library('bblr');

$bblr = new bblr\api();

$gathering = $vars['gathering'];

?>
<div class="gathering" data-guid="<?php echo $gathering->guid;?>">
	
	<div class="chat">
		<div class="messages">
		</div>
		<div class="input">
			<input type="text" name="message"/>
		</div>
	</div>
	<div class="video">
			<!--<video id="testLOCAL" autoplay="autoplay" style="position: absolute;"></video>
			<video id="testREMOTE" autoplay="autoplay" style="position: absolute;"></video>-->
	</div>
</div>
     
