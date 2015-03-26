<?php
/**
 * Carousel view
 */
 
$items = $vars['items'];

if(!$items){
	if(elgg_get_page_owner_entity()->canEdit()){
		$options = array(
			'title' => 'Click here to customise your banners.',
			'href' => elgg_get_page_owner_entity()->getURL() . '/carousel',
			'ext_bg' => 'https://d3ae0shxev0cb7.cloudfront.net//carousel/background/295332731833815040/1409532063/138117/fat'
		);
	} else {
		$options = array(
			'title' => '',
			'href' => '#',
			'ext_bg' => 'https://d3ae0shxev0cb7.cloudfront.net//carousel/background/295332731833815040/1409532063/138117/fat'
		);
	}
		
	$items = array(
		new ElggObject($options)
	);
//	return false;
}

//sort by their order..
usort($items, function($a, $b){
	return $a->order - $b->order;
});

elgg_load_js('carousel');
?>

<script>
$(document).ready(function() {
	
	// Using custom configuration
	$('.carousel').carousel(
		{
			interval: 4000,
			 pause: "false"
		}
	);
	
});
</script>

<div class="carousel fade">
	<div class="carousel-inner">
	<?php 
		$i = 0;
		foreach($items as $item){
			$link_extras = "";
			if($item->href){
				if(strpos($item->href, elgg_get_site_url()) !== FALSE)
					$target = '_self';
				else
					$target = '_blank';
				$link_extras = "href=\"{$item->href}\" target=\"$target\"";
			}

			$class = $i==0 ?'active' : '';
 			echo "<a class=\"item $class\" $link_extras>";
			if(isset($item->ext_bg) && $item->ext_bg)
				$bg = $item->ext_bg;
			else 
				$bg =  $CONFIG->cdn_url . "carousel/background/$item->guid/$item->last_updated/$CONFIG->lastcache/fat";
	
			echo "<img src=\"$bg\" style=\"top:{$item->top_offset}px\"/>";
			
			if($item->title)
				echo "<div class=\"carousel-caption\" style=\"color:$item->color\"><div class=\"inner\" style=\"background:$item->shadow\"><h3>$item->title</h3></div></div>";
	
			echo '</a>';
			$i++;
		}	
	?>
	</div>
</div>
