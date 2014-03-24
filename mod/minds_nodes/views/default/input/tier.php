<?php

$tier = $vars['tier'];
$tier_id = $vars['tier']->product_id;
$name = $tier_id;

// Display a tier view
if(elgg_view_exists('minds_nodes/tier/'. $name))
	echo elgg_view('minds_nodes/tier/'. $name, $vars); 

$currency = pay_get_currency();   
 
if ($upgrade_node = get_entity($vars['upgrade_node'], 'object')) {
	if ($tier->price == 0) {
		$title = elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/upgrade_to?tier_id='. $tier->guid."&node_guid={$upgrade_node->guid}", 'text' =>  'Free', 'class' => 'elgg-button elgg-button-action'));
	} else {
		$title = elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/upgrade_to?tier_id='. $tier->guid."&node_guid={$upgrade_node->guid}", 'text' =>  $currency['symbol'] . $tier->price, 'class' => 'elgg-button elgg-button-action'));
	}
} else {
	if ($tier->price == 0) {
		$button = elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid, 'text' =>  'Free', 'class' => 'elgg-button elgg-button-action'));
	} else {
		$button = elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid, 'text' =>  $currency['symbol'] . $tier->price, 'class' => 'elgg-button elgg-button-action'));
	}
}

?>

<h2><?php echo $tier->price == 0 ? 'FREE' : $currency['symbol'] . $tier->price; ?>/month</h2>
<div class="tier-description">
	<?php echo $tier->description; ?>
</div>
<?php 
	echo elgg_view('output/url', array(
		'is_action' => true, 
		'id' => $tier->product_id, 
		'href' => elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid, 
		'text' =>  'Select', 
		'class' => 'elgg-button elgg-button-action'
	));
?>

<?php
if (!elgg_is_logged_in()) {
?>
	<script>
		$(document).ready(function(){
	        
	        $('a#<?php echo $tier->product_id; ?>').click(function(){
				window.open("<?php echo elgg_get_site_url(); ?>tierlogin/?tier=<?php echo $tier->guid; ?>", "Please Log In", "width=800,height=650");
	        });
	        
	    });
	</script>
<?php
}
