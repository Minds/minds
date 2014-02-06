<?php
/**
 * Minds Search CC Image View
 */

$image = $vars['source'];
$full_view = $vars['full_view'];

$title = $image['title'];
$imageURL = $image['iconURL'];
$img = elgg_view('output/img', array('src'=>$imageURL));
$url = elgg_get_site_url().'search/result/'.$image['id'];

$provider = $image['source'];
$owner = $image['owner'];

if($provider == 'minds'){
	try{
		
		$entity = get_entity($image['guid'],'object');
		if($entity instanceof TidypicsImage || $entity instanceof TidypicsAlbum){
			$title = $entity->getTitle();
			$iconURL = $entity->getIconURL('small');
			$img = elgg_view('output/img', array('src'=>$iconURL));
		
			if($entity->getSubtype() == 'album'){
				$images = $entity->getImages(4);
	
				if (count($images)) {
					$img = '<ul class="tidypics-album-block">';
					foreach($images as $icon) {
						if($icon instanceof TidypicsImage){
						$img .= '<li class="tidypics-photo-item">';
						$img .= elgg_view('output/img', array('src'=>$icon->getIconURL('small')));
						$img.= '</li>';
						}
					}
					$img .= '</ul>';
				}
			
			}
		}
	} catch (Exception $e){
		
	}
}

if(!$full_view){
	
?>
<a href='<?php echo $url;?>'>
	<?php echo $img;?>
	<h3><?php echo $title;?></h3>
	<p><b><?php echo $provider;?></b></p>
</a>
<p class="license"><?php echo elgg_echo('minds:license:'.$image['license']);?></p> 
<?php 
}else {
	minds_set_metatags('og:title', $image['title']);
	minds_set_metatags('og:type', 'mindscom:photo');
	minds_set_metatags('og:url', $url);
	minds_set_metatags('og:image', $imageURL);
	minds_set_metatags('mindscom:photo', $imageURL);
	minds_set_metatags('og:description', 'License: ' . elgg_echo('minds:license:'.$image['license']));
	
	if($provider=='archive.org'||$provider=='pixabay'){
		forward($image['href']);
	}elseif($provider=='flickr'){
		//do some modification to the imageURL to get a large image
		$imageURL = str_replace('_q', '_b', $imageURL);
		echo elgg_view('output/img', array('src'=>$imageURL));
	} elseif($provider =='minds'){
		forward($entity->getURL());
	}
}?>
