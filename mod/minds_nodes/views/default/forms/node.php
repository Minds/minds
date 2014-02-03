<?php

global $CONFIG;

$node = $vars['node'];   
$owner = $node->getOwnerEntity();

$ROOT_DOMAIN = $CONFIG->minds_multisite_root_domain;
    
$currecy = pay_get_currency();	

//is the user entitled to their own domain?
?>
<div class="node-signup">
    
    <div class="email">
    	<input id="email" required type="hidden" name="email" placeholder="you@yourdomain.com" value="<?php echo $owner->email; ?>" /> </label></p>
    </div>
    
    <div class="blurb-or">Enter your network name...</div>
    
    <div class="node">
        <input id="node" type="text" name="domain_at_minds" placeholder="yournetwork" /> <?php echo $ROOT_DOMAIN; ?>
    </div>
    
	<?php if($node->allowedDomain()){ ?>

    <div class="blurb-or">...or use your own domain...</div>
    
    <div class="full-domain">
        <input id="full-domain" type="text" name="my_domain" placeholder="your.domain.com" />
    </div>

	<?php } ?>

    <input type="hidden" value="<?php echo $node->guid;?>" name="node_guid"/>        
    <input type="submit" value="Save" class="elgg-button elgg-button-submit" />
</div>
