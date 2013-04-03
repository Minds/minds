<?php
/**
 * Minds Search CC Image View
 */
 
$image = $vars['photo'];
$full_view = $vars['full_view'];

$title = strlen($image['title'])>25 ? substr($image['title'], 0, 25) . '...' : $image['title'];
$imageURL = $image['iconURL'];
$img = elgg_view('output/img', array('src'=>$imageURL));
$url = elgg_get_site_url().'search/result/'.$image['id'];
$source = $image['source'];
$owner = $image['owner'];

if(!$full_view){
	
?>
<a href='<?php echo $url;?>'>
	<div class='minds-search minds-search-item'>
		<?php echo $img;?>
		<h3><?php echo $title;?></h3>
		<p><?php echo $source;?> <br/>
		   <?php echo $owner;?></p>
	</div>
</a>
<?php 
}else {
	minds_set_metatags('og:title', $image['title']);
	minds_set_metatags('og:type', 'mindscom:photo');
	minds_set_metatags('og:url', $url);
	minds_set_metatags('og:image', $imageURL);
	minds_set_metatags('mindscom:photo', $imageURL);
	minds_set_metatags('og:description', 'License: ' . elgg_echo('minds:license:'.$image['license']));
	
	if($source=='archive.org'||$source=='pixabay'){
		forward($image['href']);
	}elseif($source=='flickr'){
		//do some modification to the imageURL to get a large image
		$imageURL = str_replace('_q', '_b', $imageURL);
		echo elgg_view('output/img', array('src'=>$imageURL, 'width'=>970));
	}
}?>