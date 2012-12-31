<?php
/**
 * Minds Search CC Image View
 */
 
$image = $vars['photo'];

$title = strlen($image['title'])>25 ? substr($image['title'], 0, 25) . '...' : $image['title'];
$img = elgg_view('output/img', array('src'=>$image['iconURL']));
$source = $image['source'];
?>
<a href='<?php echo $image['href'];?>'>
	<div class='minds-search minds-search-item'>
		<?php echo $img;?>
		<h3><?php echo $title;?></h3>
		<p>Source: <?php echo $source;?><br/>
			Type: Image
		</p>
	</div>
</a>
