<?php
/**
 * Minds Search CC Image View
 */
 
$video = $vars['video'];
$full_view = $vars['full_view'];

$title = strlen($video['title'])>25 ? substr($video['title'], 0, 25) . '...' : $video['title'];
$img = elgg_view('output/img', array('src'=>$video['iconURL']));
$source = $video['source'];

if(!$full_view){
?>
<a href='<?php echo elgg_get_site_url().'search/result/'.$video['id'];?>'>
	<div class='minds-search minds-search-item'>
		<?php echo $img;?>
		<h3><?php echo $title;?></h3>
		<p>Source: <?php echo $video['source'];?><br/>
			Type: Video</p>
	</div>
</a>
<?php 
}else {
	if($source=='archive.org'){
		forward($url);
	}elseif($source=='youtube'){
		$yt_id = str_replace('youtube_', '', $video['id']);
		echo '<iframe src="http://youtube.com/embed/'.$yt_id.'" width="975px" height="500px"></iframe>';
	}
}