<?php
/**
 * Minds Search CC Image View
 */
 
$sound = $vars['source'];
$full_view = $vars['full_view'];

$title = strlen($sound['title'])>25 ? substr($sound['title'], 0, 25) . '...' : $sound['title'];
$img = elgg_view('output/img', array('src'=>$sound['iconURL']));
$provider = $sound['provider'];

if(!$full_view){
?>
<a href='<?php echo elgg_get_site_url().'search/result/'.$sound['id'];?>'>
		<?php echo $img;?>
		<h3><?php echo $title;?></h3>
		<p>Source: <?php echo $provider;?><br/></p>
</a>
<?php 
} else {
	minds_set_metatags('og:title', $sound['title']);
	minds_set_metatags('og:type', 'mindscom:sound');
	minds_set_metatags('og:url', $url);
	minds_set_metatags('og:image', $sound['iconURL']);
	minds_set_metatags('og:description', 'License: ' .elgg_echo('minds:license:'.$sound['license']));
	if($source=='freesound'){
		$fs_id = str_replace('freesound_', '', $sound['id']);
		echo '<iframe src="http://www.freesound.org/embed/sound/iframe/'.$fs_id.'/simple/large" width="975px" height="300px"></iframe>';
		minds_set_metatags('og:video', 'http://www.freesound.org/embed/sound/iframe/'.$fs_id.'/simple/large');
	}elseif($source=='soundcloud'){
		$sc_id = str_replace('soundcloud_', '', $sound['id']);
		echo '<iframe width="975px" height="175px" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'.$sc_id.'"></iframe>';
		minds_set_metatags('og:video','https://player.soundcloud.com/player.swf?url=https%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'.$sc_id);
		minds_set_metatags('og:video:type', 'application/x-shockwave-flash');
		minds_set_metatags('og:video:width', 480);
		minds_set_metatags('og:video:height', 98);	
	}elseif($source=='ccmixter' || $source =='archive.org'){
		forward($sound['href']);
	}
}
