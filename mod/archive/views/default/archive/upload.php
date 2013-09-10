<?php
/* 
 * Archive upload selection page 
 * 
 */

 ?>
 <div class="archive-upload">
	<div class="column">
		<a href="<?php echo elgg_get_site_url();?>archive/upload/videoaudio">
			<img src="<?php echo elgg_get_site_url();?>mod/minds/graphics/icons/videoaudio.png"/>
			<h3><?php echo elgg_echo('minds:archive:upload:videoaudio');?></h3>
		</a>
	</div>
	
	<div class="column">
		<a href="<?php echo elgg_get_site_url();?>archive/upload/album/create">
			<img src="<?php echo elgg_get_site_url();?>mod/minds/graphics/icons/album.png"/>
			<h3><?php echo elgg_echo('minds:archive:album:create');?></h3>
		</a>
	</div>
	
	<div class="column">
		<a href="<?php echo elgg_get_site_url();?>archive/upload/others">
			<img src="<?php echo elgg_get_site_url();?>mod/minds/graphics/icons/others.png"/>
			<h3><?php echo elgg_echo('minds:archive:upload:others');?></h3>
		</a>
	</div>

     <div class="column">
         <a href="<?php echo elgg_get_site_url();?>archive/upload/angularJS">
             <img src="<?php echo elgg_get_site_url();?>mod/minds/graphics/icons/others.png"/>
             <h3><?php echo elgg_echo('minds:archive:angularUploader');?></h3>
         </a>
     </div>

</div>
