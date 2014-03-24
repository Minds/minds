<?php

$ia = elgg_set_ignore_access();

// If We've previously selected a tier while logged out, and have now logged in, then we forward them.
if (elgg_is_logged_in() && $_SESSION['__tier_selected'])
{
    $tier = get_entity($_SESSION['__tier_selected']);
    $url = elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid;
	$url = elgg_add_action_tokens_to_url($url);

    unset ($_SESSION['__tier_selected']);

    forward($url);
}

// Get tiers
$tiers = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'minds_tier',
	'limit' => 3
));

//sort the tiers by price
usort($tiers, function($a, $b){
	return $a->price - $b->price;
});

?>
<div id="tiers">
	<div class="row thead">
		<div class="cell feature">&nbsp;</div>
		<?php 
			foreach($tiers as $tier){
				echo '<div class="cell">$'. $tier->price . '/month</div>';
			}
		?>
	</div>
	<?php 
		foreach(minds_tiers_get_features() as $feature){
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
				$button =  elgg_view('output/url', array(
					'is_action' => true, 
					'id' => 'tier-select-button', 
					'data-guid' => $tier->guid,
					'href' => elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid, 
					'text' =>  'Select', 
					'class' => 'elgg-button elgg-button-action'
				));
				echo '<div class="cell">'. $button . '</div>';
			}
		?>
	</div>
</div>

<?php if(!elgg_is_logged_in()){ ?>
<script>
		$(document).ready(function(){
	        
	        $('a#tier-select-button').click(function(){
				window.open("<?php echo elgg_get_site_url(); ?>tierlogin/?tier="+$(this).attr('data-guid'), "Please Log In", "width=800,height=650");
	        });
	        
	    });
	</script>
<?php
}
return;    
if ($tiers) {
     
?>
	<ul id="tiers-selection" class="elgg-list">
	  
	    <?php foreach ($tiers as $tier) { ?>
	    
		    <li class="elgg-item tier tier-<?php echo $tier->product_id; ?>">
		        <?php echo  elgg_view('input/tier', array('tier' => $tier)); ?>
		    </li>
	    
	    <?php } ?>
	    <br class="clearfix" />
	</div>

<?php 
// Returning from an order, so we need to poll while Paypal processes the payment and pings our IPN
	if (get_input('auth')) { 
?>
		<script>
		
		    $('#tiers-selection').html("<p>Processing payment, please wait...</p>");
		    setTimeout("location.reload(true);", 5000);
		    
		</script>
<?php 
	} 

} else {
	
	echo "<p>No tiers have been defined. Please make sure you have activated the minds_nodes plugin and then create some payment tiers!</p>";

}

elgg_set_ignore_access($ia);