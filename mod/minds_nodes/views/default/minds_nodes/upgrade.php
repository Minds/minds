<div class="my-node">
    <?php echo elgg_view_entity($vars['object'], array('hide_buttons' => true)); ?>
</div>
<?php


    $ia = elgg_set_ignore_access();
    
    
    // Get tiers
    $tiers = elgg_get_entities(array(
       'type' => 'object',
        'subtype' => 'minds_tier'
    ));
    
    if ($tiers) {
        

?>
<ul id="tiers-selection" class="elgg-list">
  
    <?php foreach ($tiers as $tier) { 
        if (($tier->guid!=$vars['object']->tier_guid) && ($vars['object']->price < $tier->price)) {
        ?>
    
    <li class="elgg-item tier tier-<?php echo $tier->product_id; ?>">
        <?php echo  elgg_view('input/tier', array('tier' => $tier, 'upgrade_node' => $vars['object']->guid)); ?>
        
    </li>
    
    <?php } }?>
    <br class="clearfix" />
</div>


<?php

    }
 else {
?>
    
<p>No tiers have been defined. Please make sure you have activated the minds_tiers plugin and then create some payment tiers!</p>

<?php
}

    elgg_set_ignore_access($ia);
    
?>

