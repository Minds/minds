<?php
/**
 * Minds Search CC Image View
 */
 
$sound = $vars['sound'];
$full_view = $vars['full_view'];

$title = strlen($sound['title'])>25 ? substr($sound['title'], 0, 25) . '...' : $sound['title'];
$img = elgg_view('output/img', array('src'=>$sound['iconURL']));
$source = $sound['source'];

if(!$full_view){
?>
<a href='<?php echo elgg_get_site_url().'search/result/'.$sound['id'];?>'>
	<div class='minds-search minds-search-item'>
		<?php echo $img;?>
		<h3><?php echo $title;?></h3>
		<p>Source: <?php echo $sound['source'];?><br/>
			Type: Sound</p>
	</div>
</a>
<?php 
} else {
	minds_set_metatags('og:title', $sound['title']);
	minds_set_metatags('og:type', 'video.other');
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
		minds_set_metatags('og:video','https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'.$sc_id);
	}elseif($source=='ccmixter'){
		forward();
	}
}
