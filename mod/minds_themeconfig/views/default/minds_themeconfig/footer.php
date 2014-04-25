<div class="static-footer">
    <div class="copyright">
	<?php echo elgg_get_plugin_setting('copyright', 'minds_themeconfig'); ?>
</div>

<div class="footer-social-links">
<?php

$networks = minds_config_social_links();

foreach($networks as $network => $n){
	if($url = $n['url']){
		$icon = $n['icon'];
		echo "<a class=\"entypo\" href=\"$url\">$icon</a>";
	}
}	
?>
</div>
</div>