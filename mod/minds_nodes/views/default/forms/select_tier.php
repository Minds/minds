<?php

    $ia = elgg_set_ignore_access();
    
    // If We've previously selected a tier while logged out, and have now logged in, then we forward them.
    if (elgg_is_logged_in() && $_SESSION['__tier_selected'])
    {
        $tier = get_entity($_SESSION['__tier_selected']);
        $url = elgg_get_site_url(). "action/pay/basket/add?type_guid={$tier->guid}&title={$tier->title}&description={$tier->description}&price={$tier->price}&quantity=1&recurring=y";
        $url = elgg_add_action_tokens_to_url($url);

        unset ($_SESSION['__tier_selected']);

        forward($url);
    }
    
    // Get tiers
    $tiers = elgg_get_entities(array(
       'type' => 'object',
        'subtype' => 'minds_tier'
    ));
    
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


<?php } ?>

<?php

    }
 else {
?>
    
<p>No tiers have been defined. Please make sure you have activated the minds_tiers plugin and then create some payment tiers!</p>

<?php
}

    elgg_set_ignore_access($ia);
    
    ?>
