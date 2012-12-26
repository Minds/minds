<?php
/**
 * Minds Search CC Image View
 */
 
$video = $vars['video'];

$title = strlen($video->title)>25 ? substr($video->title, 0, 25) . '...' : $video->title;
$img = elgg_view('output/img', array('src'=>$video->iconURL));
$source = $video->source;
?>
<a href='<?php echo $video->href?>'>
	<div class='minds-search minds-search-item'>
		<?php echo $img;?>
		<h3><?php echo $title;?></h3>
		<p>Source: <?php echo $video->source;?></p>
	</div>
</a>
