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
<div class="default-description">
    <h2><?php echo $tier->title; ?></h2>
    <p><?php echo $tier->description; ?></p>
</div>
<?php 
    }
    ?>
    
    <div class="tier_selection">
        <?php echo pay_basket_add_button($tier->guid, $tier->title, $tier->description, $tier->price, 1); ?>
    </div>