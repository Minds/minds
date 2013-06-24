<?php

    $ia = elgg_set_ignore_access();
    
    // Get tiers
    $tiers = elgg_get_entities(array(
       'type' => 'object',
        'subtype' => 'minds_tier'
    ));

?>
<div class="tiers">
  
    <p><?php echo elgg_echo('minds:tier:blurb');?></p>
    
    <?php
        foreach ($tiers as $tier) {
    
            ?>
    
    <div class="tier tier-<?php echo $tier->product_id; ?>">
        <?php echo  elgg_view('input/tier', array('tier' => $tier->product_id)); ?>
    </div>
    
    
            <?php
            
        }
    ?>
    <br class="clearfix" />
    <?php echo elgg_view('input/submit', array('value' => 'Select tier...')); ?>
</div>
<?php

    elgg_set_ignore_access($ia);
    
    ?>