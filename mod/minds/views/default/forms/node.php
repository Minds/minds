<?php

    global $CONFIG;
    
    $ROOT_DOMAIN = $CONFIG->minds_multisite_root_domain;
    
    
?>
<div class="node-signup">
    
    <div class="node">
        <input id="node" type="text" name="domain_at_minds" placeholder="yournetwork" /> .<?php echo $ROOT_DOMAIN; ?>
    </div>
    
    <div class="blurb-or">...or use your own domain...</div>
    
    <div class="full-domain">
        <input id="full-domain" type="text" name="my_domain" placeholder="your.domain.com" />
    </div>
    
    <input type="submit" value="Save" />
</div>
