<?php
//tiers
// Get tiers
$tiers = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'minds_tier',
	'limit' => 3
));
if(count($tiers) != 3){
	$tiers[] = new MindsTier();
	$tiers->save();
}

//sort the tiers by price
usort($tiers, function($a, $b){
	return $a->price - $b->price;
});

echo elgg_view('input/submit', array('value' => 'Save'));
?>
<div id="tiers" class="admin">
	<div class="row thead">
		<div class="cell feature">&nbsp;</div>
		<?php 
			foreach($tiers as $tier){
				echo '<div class="cell">'. elgg_view('input/text', array('name'=>"$tier->guid:price", 'value'=>$tier->price)) . '/month</div>';
			}
		?>
	</div>
	<?php 
		foreach(minds_tiers_get_features() as $feature){
			echo '<div class="row">';
				echo '<div class="cell feature">'.$feature.'</div>';
				foreach($tiers as $tier)
					echo '<div class="cell">'. elgg_view('input/text', array('name'=>"$tier->guid:$feature", 'value'=>$tier->$feature)) . '</div>';
			echo '</div>';
		}
	?>
</div>