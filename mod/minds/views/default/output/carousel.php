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
?>
<script>
$(document).ready(function() {
	
	// Using custom configuration
	$("#<?php echo $id;?>").carouFredSel({
		width: '100%',
		items: {
			visible: 3,
			start: -1
		},
		scroll: {
			items: 1,
			duration: 1000,
			timeoutDuration: 3000
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
		foreach($divs as $title => $params){
			echo '<div style="background: url(' . $params['bg_url'] . ');">';
			echo '<h2>' . $title . '</h2>';
			echo '<h3>' . $subtitle . '</h3>';
			echo '</div>';
		}	
	?>
	</div>
	<div class="pagination" id="<?php echo $id;?>_pag"></div>
</div>
