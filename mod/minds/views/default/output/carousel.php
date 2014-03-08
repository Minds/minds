<?php
/**
 * View for carousels
 * 
 */
elgg_load_js('carouFredSel');

//$entities = elgg_extract('entities', $vars);
$divs = elgg_extract('divs', $vars);
$id =  elgg_extract('id',$vars, rand());

?>
<script>
$(document).ready(function() {
	
	// Using custom configuration
	$("#<?php echo $id;?>").carouFredSel({
		items				: 1,
		direction			: "left",
		swipe				: true,
		infinite			: true,
		circular			: false,
	//	pagination			: "#<?php echo $id;?>_pag",
		prev	: { 
			button  : "#<?php echo $id;?>_prev",
			key		: "left",
		},
		next	: { 
			button  : "#<?php echo $id;?>_next",
			key		: "right",
		},
		scroll : {
			items			: 1,
			duration		: 600,							
			onBefore: function( data ) {
               		 data.items.old.find('h2').animate({opacity:0});
               	 	data.items.visible.eq(1).find('h2').animate({opacity:1});
            	}
		}					
	});	
});
</script>
<div id="hz_carousel">
	<div id="<?php echo $id;?>">
	<?php 
		foreach($divs as $div){
			echo '<div>';
			echo $div;
			echo '</div>';
		}	
	?>
	</div>
	<div class="pagination" id="<?php echo $id;?>_pag"></div>
</div>
