<?php

global $CONFIG;

$node = $vars['node'];   
$owner = $node->getOwnerEntity();

$ROOT_DOMAIN = $CONFIG->minds_multisite_root_domain ?: '.minds.com';
    

//is the user entitled to their own domain?
?>
<div class="node-signup">
    
    <div class="blurb-or">Enter your network name...</div>
    
    <div class="node">
        <input id="node" type="text" name="domain_at_minds" placeholder="yournetwork" value="<?php echo str_replace($ROOT_DOMAIN, '',$node->domain); ?>"/> <?php echo $ROOT_DOMAIN; ?>
    </div>
    
	<?php if($node->allowedDomain()){ ?>

	    <div class="blurb-or">...or use your own domain...</div>
	    
	    <div class="full-domain">
	        <input id="full-domain" type="text" name="domain" placeholder="your.domain.com" value="<?php echo $node->domain; ?>"/>
	    </div>
	    
	    <div class="info">
	    	<i>HINT! Set your CName DNS record to <b>multisite2loadbalancer2-1442974952.us-east-1.elb.amazonaws.com</b></i>
	    	
	    	<br/><br/>
	    </div>
	    

	<?php } ?>

    <input type="hidden" value="<?php echo $node->guid;?>" name="node_guid"/>        
    <input type="submit" value="Save" class="elgg-button elgg-button-submit" />
</div>
