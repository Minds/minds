<?php
// Get tiers
$tiers = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'minds_tier',
	'limit' => 4
));

//sort the tiers by price
usort($tiers, function($a, $b){
	if($a->price === 'Custom')
		return 1000;
	return $a->price - $b->price;
});

if(isset($vars['hide_free'])){
	foreach($tiers as $guid => $tier){
		if($tier->price == 0)
			unset($tiers[$guid]);
	}
}
?>
<div id="tiers">
	<div class="row thead">
		<div class="cell feature">&nbsp;</div>
		<?php 
			foreach($tiers as $tier){
				if($tier->price === 'Custom')
					echo '<div class="cell">'. $tier->price . '</div>';
				elseif($tier->price === 0)
					echo '<div class="cell">Free</div>';
				else  
					echo '<div class="cell">$'. $tier->price . '/month</div>';
			}
		?>
	</div>
	<?php 
		foreach(minds\plugin\minds_nodes\start::tiersGetFeatures() as $feature){
			echo '<div class="row">';
				echo '<div class="cell feature">'.elgg_echo("minds_tiers:feature:$feature").'</div>';
				foreach($tiers as $tier)
					echo '<div class="cell">'. (isset($tier->$feature) ? $tier->$feature : 'X') . '</div>';
			echo '</div>';
		}
	?>
	<div class="row tfoot">
		<div class="cell feature">See the terms and conditions</div>
		<?php 
			foreach($tiers as $tier){
				$class = "tier-$tier->price";
				$button =  elgg_view('output/url', array(
					'is_action' => true, 
					'id' => 'tier-select-button', 
					'data-guid' => $tier->guid,
					'data-price' => $tier->price,
					'href' => '#', 
					//'text' => $tier->price > 0 ? 'Coming soon' : 'Select', 
					'text' => $tier->price === 'Custom' ? 'Contact Us' : 'Select',
					'class' => 'elgg-button elgg-button-action '.$class,
				));
				echo '<div class="cell">'. $button . '</div>';
			}
		?>
	</div>
</div>