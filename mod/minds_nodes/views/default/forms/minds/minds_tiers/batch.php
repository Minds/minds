<?php
//tiers
// Get tiers
$tiers = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'minds_tier',
	'limit' => 3
));
if(count($tiers) != 3){
	$tier = new MindsTier();
	$tier->owner_guid = elgg_get_logged_in_user_guid();
    $tier->container_guid = elgg_get_logged_in_user_guid();
    $tier->access_id = ACCESS_PUBLIC;
	$tier->save();
	$tiers[] = $tier;
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
		foreach(minds\plugin\minds_nodes\start::tiersGetFeatures() as $feature){
			echo '<div class="row">';
				echo '<div class="cell feature">'.$feature.'</div>';
				foreach($tiers as $tier)
					echo '<div class="cell">'. elgg_view('input/text', array('name'=>"$tier->guid:$feature", 'value'=>$tier->$feature)) . '</div>';
			echo '</div>';
		}
	?>
</div>
