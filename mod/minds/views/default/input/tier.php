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
        <?php 
        if (elgg_is_logged_in()) 
            if ($tier->price == 0) {
                $currecy = pay_get_currency();	
                echo elgg_view('output/url', array('is_action' => true, 'id' => $tier->product_id, 'href' => elgg_get_site_url() . 'action/select_free_tier?tier_id='. $tier->guid, 'text' => $currecy['symbol'] . $tier->price . ' - Sign Up Now', 'class' => 'pay buynow login'));
            }
            else
                echo pay_basket_add_button($tier->guid, $tier->title, $tier->description, $tier->price, 1, true); 
        else {
             $currecy = pay_get_currency();	
             echo elgg_view('output/url', array('id' => $tier->product_id, 'href' => '#', 'text' => $currecy['symbol'] . $tier->price . ' - Buy Now', 'class' => 'pay buynow free'));
        }
?>
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