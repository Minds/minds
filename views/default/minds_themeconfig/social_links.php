<?php


if(minds\core\minds::detectMultisite())
	return true;
?>

<div class="footer-social-links">
<?php
$networks = array(
	'facebook'=>array(
		'url' => 'http://facebook.com/mindsdotcom',
		'icon' => '&#62221;'
	),
	'twitter'=>array(
                'url' => 'http://twitter.com/mindsdotcom',
                'icon' => '&#62218;'
        )
);
foreach($networks as $network => $n){
	if($url = $n['url']){
		$icon = $n['icon'];
		echo "<a class=\"entypo\" href=\"$url\" target=\"_blank\">$icon</a>";
	}
}
?>
</div>	
