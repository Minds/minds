<?php
/**
 * View for carousels
 * 
 */
elgg_load_js('carouFredSel');

//$entities = elgg_extract('entities', $vars);
$divs = elgg_extract('divs', $vars);
//$id =  elgg_extract('id',$vars, rand());
$id = 'carousel'; //debug override;
$subtitle = elgg_extract('subtitle', $vars);

$items = elgg_get_entities(array(
			'type'=>'object',
			'subtype'=>'carousel_item'
		));
?>
<script>
$(document).ready(function() {
	
	// Using custom configuration
	$("#<?php echo $id;?>").carouFredSel({
		width: '100%',
		items: {
			visible: <?php echo count($items) > 3 ? 3 : 1 ?>,
			start: <?php echo count($items) > 3 ? -1 : 1 ?>
		},
		scroll: {
			items: 1,
			duration: 1500,
			timeoutDuration: 3500
		},
		direction			: "left",
		swipe				: true,
		infinite			: true,
		circular			: true,
	//	pagination			: "#<?php echo $id;?>_pag",
		prev	: { 
			button  : "#<?php echo $id;?>_prev",
			key		: "left",
		},
		next	: { 
			button  : "#<?php echo $id;?>_next",
			key		: "right",
		}			
	});	
});
</script>

<div id="carousel_wrapper">
	<div id="<?php echo $id;?>">
	<?php 
		
		foreach($items as $item){
			echo '<div style="background: url(' . elgg_get_site_url() . "/carousel/background/$item->guid" . ');">';
			//echo '<div>';
			echo '<h2>' . $item->title . '</h2>';
			echo '<h3>' . $subtitle . '</h3>';
			echo '</div>';
		}	
	?>
	</div>
	<div class="pagination" id="<?php echo $id;?>_pag"></div>
</div>
