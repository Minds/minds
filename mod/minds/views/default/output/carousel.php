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
			'ext_bg' => 'http://d2ka7pmjfsr8hl.cloudfront.net/www.minds.org//carousel/background/346019177162608640/1407873642/0'
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
				$link_extras = "href=\"{$item->href}\" target=\"_blank\"";
			}

			$class = $i==0 ?'active' : '';
 			echo "<a class=\"item $class\" $link_extras>";
			if(isset($item->ext_bg) && $item->ext_bg)
				$bg = $item->ext_bg;
			else 
				$bg =  $CONFIG->cdn_url . "/carousel/background/$item->guid/$item->last_updated/$CONFIG->lastcache/".elgg_get_plugin_setting('style','minds');
	
			echo "<img src=\"$bg\" />";
			echo "<div class=\"carousel-caption\" style=\"color:$item->color\"><div class=\"inner\" style=\"background:$item->shadow\"><h3>$item->title</h3></div></div>";
	
			echo '</a>';
			$i++;
		}	
	?>
	</div>
</div>
