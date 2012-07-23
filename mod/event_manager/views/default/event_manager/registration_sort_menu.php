<?php

	 $url = $vars['url'] . EVENT_MANAGER_BASEURL."/registration/list/".$vars["eventguid"];

?>
<div id="elgg_horizontal_tabbed_nav">
	<ul>
		<li <?php if($vars["filter"] == "waiting") echo "class='selected'"; ?>>	
			<a href="<?php echo $url; ?>?filter=waiting"><?php echo elgg_echo('event_manager:registration:list:navigation:waiting'); ?></a>
		</li>
		<li <?php if($vars["filter"] == "attending") echo "class='selected'"; ?>>
			<a href="<?php echo $url; ?>?filter=attending"><?php echo elgg_echo('event_manager:registration:list:navigation:attending'); ?></a>
		</li>
	</ul>
</div>