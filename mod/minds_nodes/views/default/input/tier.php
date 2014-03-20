<?php

$tier = $vars['tier'];
$tier_id = $vars['tier']->product_id;
$name = $tier_id;


?>
    
    <?php 
    // Display a tier view
    $description = elgg_view('minds/tier/'. $name, $vars); 
    if ($description) 
        echo $description;
    else
    {
        ?>
<?php 
    }
	$currency = pay_get_currency();   
 
        if (elgg_is_logged_in()){ 
            if ($upgrade_node = get_entity($vars['upgrade_node'], 'object')) {
                if ($tier->price == 0) {
               		$title = elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/upgrade_to?tier_id='. $tier->guid."&node_guid={$upgrade_node->guid}", 'text' =>  'Free', 'class' => 'pay buynow login'));
           	 } else {
        		$title = elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/upgrade_to?tier_id='. $tier->guid."&node_guid={$upgrade_node->guid}", 'text' =>  $currency['symbol'] . $tier->price, 'class' => 'pay buynow login'));

		}
            } else {
       		if ($tier->price == 0) {
               		$title = elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid, 'text' =>  'Free', 'class' => 'pay buynow login'));
           	 } else {
        		$title = elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/select_tier?tier_id='. $tier->guid, 'text' =>  $currency['symbol'] . $tier->price, 'class' => 'pay buynow login'));

		}
            }
	} else {
             $currecy = pay_get_currency();	
             $title = elgg_view('output/url', array('id' => $tier->product_id, 'href' => '#', 'text' => $currecy['symbol'] . $tier->price, 'class' => 'pay buynow free'));
        }
?>

<div class="default-description">
    <h2><?php echo $title; ?></h2>
    <p><b><?php echo $tier->title; ?></b><?php echo $tier->description; ?></p>
</div>

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
} ?>
