<div class="footer-social-links">
<?php

$networks = minds_config_social_links();

foreach($networks as $network => $n){
	if($url = $n['url']){
		$icon = $n['icon'];
		echo "<a class=\"entypo\" href=\"$url\" target=\"_blank\">$icon</a>";
	}
}	
?>
</div>