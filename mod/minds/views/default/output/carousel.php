<?php
/**
 * View for carousels
 * 
 */
global $CONFIG;
//elgg_load_js('carousel');

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

//sort the tiers by price
usort($items, function($a, $b){
	return $a->order - $b->order;
});

?>
<script>
$(document).ready(function() {
	
	// Using custom configuration
	$('.carousel').carousel();
	
});
</script>

<div class="carousel fade">
	<div class="carousel-inner">
	<?php 
		$i = 0;
		foreach($items as $item){
			$class = $i==0 ?'active' : '';
 			echo "<div class=\"item $class\">";
			//echo '<div>';
			//echo '<h3>' . $subtitle . '</h3>';
			$bg =  elgg_get_site_url() . "/carousel/background/$item->guid/$item->last_updated/$CONFIG->lastcache";
			echo "<img src=\"$bg\" />";
			echo "<div class=\"carousel-caption\" style=\"color:$item->color\"><h3>$item->title</h3></div>";
			
			echo '</div>';
			$i++;
		}	
	?>
	</div>
</div>
