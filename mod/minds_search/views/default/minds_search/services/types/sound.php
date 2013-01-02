<?php
/**
 * Minds Search CC Image View
 */
 
$sound = $vars['sound'];

$title = strlen($sound['title'])>25 ? substr($sound['title'], 0, 25) . '...' : $sound['title'];
$img = elgg_view('output/img', array('src'=>$sound['iconURL']));
$source = $sound->source;
?>
<a href='<?php echo $sound['href'];?>'>
	<div class='minds-search minds-search-item'>
		<?php echo $img;?>
		<h3><?php echo $title;?></h3>
		<p>Source: <?php echo $sound['source'];?><br/>
			Type: Sound</p>
	</div>
</a>
