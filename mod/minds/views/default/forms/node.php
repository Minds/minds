<?php

    global $CONFIG;
    
    $ROOT_DOMAIN = $CONFIG->minds_multisite_root_domain;
    
    $ia = elgg_set_ignore_access();
    
    $order = $vars['order'];
    $tier = get_entity($order->object_guid);
    $currecy = pay_get_currency();	
?>
<div class="node-signup">
    
    <?php if ($order->payment_used) { ?>
    
    <div class="tier_details">
        <div class="default-description">
            <h2><?php echo $tier->title; ?></h2>
            <p><?php echo $tier->description; ?></p>
            
        </div>
        <p class ="pay buynow">Current tier! <a href="<?php echo elgg_get_site_url(); ?>pay">View payment history...</a></p>
    </div>
    
    
    <?php } else { ?>
    
    <div class="tier_details">
        <div class="default-description">
            <h2><?php echo $tier->title; ?></h2>
            <p><?php echo $tier->description; ?></p>
            
        </div>
        <p class ="pay buynow"><?php echo $currecy['symbol'] . $tier->price; ?></p>
    </div>
    
    <div class="email">
        <?php /* <p><label>Enter your email address:<br /> */ ?>
                <input id="email" required type="hidden" name="email" placeholder="you@yourdomain.com" value="<?php echo elgg_get_logged_in_user_entity()->email; ?>" /> </label></p>
    </div>
    
    <div class="blurb-or">Enter your network name...</div>
    
    <div class="node">
        <input id="node" type="text" name="domain_at_minds" placeholder="yournetwork" /> <?php echo $ROOT_DOMAIN; ?>
    </div>
    
    <div class="blurb-or">...or use your own domain...</div>
    
    <div class="full-domain">
        <input id="full-domain" type="text" name="my_domain" placeholder="your.domain.com" />
    </div>
        
    <input type="submit" value="Save" class="elgg-button elgg-button-submit" />
    <?php } ?>
</div>
<?php
elgg_set_ignore_access($ia);
?>