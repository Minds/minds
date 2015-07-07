<?php
/**
 * View for carousels
 * 
 */
global $CONFIG;
elgg_load_js('carousel');

//$entities = elgg_extract('entities', $vars);
$divs = elgg_extract('divs', $vars);
//$id =  elgg_extract('id',$vars, rand());
$id = 'carousel'; //debug override;
$subtitle = elgg_extract('subtitle', $vars);

$items = elgg_get_entities(array(
			'type'=>'object',
			'subtype'=>'carousel_item',
			'limit' => 0
		));
if(!$items){
	$items = array(
		new ElggObject(array(
			'title' => 'Click here to customise your carousel.',
			'href' => elgg_get_site_url() . 'admin/appearance/carousel',
			'ext_bg' => 'https://d3ae0shxev0cb7.cloudfront.net//carousel/background/295332731833815040/1409532063/138117/fat'
		))
	);
//	return false;
}
//sort the tiers by price
usort($items, function($a, $b){
	return $a->order - $b->order;
});

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
                $href = htmlspecialchars($item->href, ENT_QUOTES);
				$link_extras = "href=\"{$href}\" target=\"_blank\"";
			}

            $item->title = strip_tags($item->title);
            $item->subtitle = strip_tags($item->subtitle);

			$class = $i==0 ?'active' : '';
 			echo "<a class=\"item $class\" $link_extras>";
			if(isset($item->ext_bg) && $item->ext_bg)
				$bg = $item->ext_bg;
			else 
				$bg =  $CONFIG->cdn_url . "/carousel/background/$item->guid/$item->last_updated/$CONFIG->lastcache/fat";

            $offset = strip_tags($item->top_offset);    
            $color = strip_tags($item->color);
            $shadow = strip_tags($item->shadow);
			echo "<img src=\"$bg\" style=\"top:{$offset}px\"/>";
			echo "<div class=\"carousel-caption\" style=\"color:$color\"><div class=\"inner\" style=\"background:$shadow\"><h3>$item->title</h3><p style=\"color:$color\">$item->subtitle</p></div></div>";
	
			echo '</a>';
			$i++;
		}	
	?>
	</div>
</div>
