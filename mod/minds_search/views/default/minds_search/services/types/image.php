<?php
/**
 * Minds Search CC Image View
 */
 
$image = $vars['photo'];
$full_view = $vars['full_view'];

$title = strlen($image['title'])>25 ? substr($image['title'], 0, 25) . '...' : $image['title'];
$imageURL = $image['iconURL'];
$img = elgg_view('output/img', array('src'=>$imageURL));
$url = $image['href'];
$source = $image['source'];

if(!$full_view){
	
?>
<a href='<?php echo $url;?>'>
	<div class='minds-search minds-search-item'>
		<?php echo $img;?>
		<h3><?php echo $title;?></h3>
		<p>Source: <?php echo $source;?><br/>
			Type: Image
		</p>
	</div>
</a>
<?php 
}else {
	if($source=='archive.org'){
		forward($url);
	}elseif($source=='flickr'){
		//do some modification to the imageURL to get a large image
		$imageURL = str_replace('_q', '_c', $imageURL);
		echo elgg_view('output/img', array('src'=>$imageURL, 'width'=>725));
	}
}?>