<?php

$tier_id = $vars['tier'];
$name = $tier_id;
?>
    
    <?php 
    // Display a tier view
    echo elgg_view('minds/tier/'. $name, $vars); 
    ?>
    
    <div class="tier_selection">
        <input type="radio" name="<?php echo $name; ?>" value="<?php echo  $tier_id; ?>" <?php if ($vars['selected']) echo 'checked'; ?> /> Select <?php echo elgg_echo('minds:tier:'.$name); ?> tier
    </div>