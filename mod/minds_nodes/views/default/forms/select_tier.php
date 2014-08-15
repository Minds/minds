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
				$class = $tier->price > 0 ? ' disabled' :'';
				$button =  elgg_view('output/url', array(
					'is_action' => true, 
					'id' => 'tier-select-button', 
					'data-guid' => $tier->guid,
					'href' => $tier->price > 0 ? '#' : elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid, 
					'text' => $tier->price > 0 ? 'Coming soon' : 'Select', 
					'class' => 'elgg-button elgg-button-action'.$class,
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
			//	window.open("<?php echo elgg_get_site_url(); ?>tierlogin/?tier="+$(this).attr('data-guid'), "Please Log In", "width=800,height=650");
	        });
	        
	    });
	</script>
<?php
}


/**
 * Section 2 
 * Select your domain...
 */
?>
<div class="nodes-table domain">
	<div class="row thead">
		<div class="cell feature">Select Domain</div>
		<div class="cell">
			<input type="text" placeholder="eg. www.myawesomesite.com" name="domain"/>
		</div>
	</div>
</div>


<?php
/**
 * Step 3. Create a minds account
 * 
 */
 
if(!elgg_is_logged_in()){
?>
<div class="nodes-table account">
	<div class="row thead">
		<div class="cell feature">Select Domain</div>
		<div class="cell">
			<input type="text" placeholder="eg. www.myawesomesite.com" name="domain"/>
		</div>
	</div>
</div>
<?php 
}
