<?php

//TODO: Validation

    global $CONFIG;
    
    $ROOT_DOMAIN = $CONFIG->minds_multisite_root_domain;
    
    // Should probably cache this.
    $domains = json_decode(file_get_contents($CONFIG->multisite_endpoint . 'webservices/get_user_domains.php?minds_user_id=' .$vars['minds_user_guid']));
    $my_domains = $domains->domains;
?>
<input type="hidden" name="minds_user_id" value="<?php echo $vars['minds_user_guid'];?>" />

<?php
    for ($n = 0; $n < 10; $n++) {
        ?>

                <p><input type="text" name="domains[]" placeholder="e.g. foo.<?php echo $ROOT_DOMAIN; ?>" value="<?php echo  $my_domains[$n];?>" /> <?php
                    if ($my_domains[$n]) {
                        ?>
                    <a href="<?php echo elgg_get_site_url();?>register/testping?domain=<?php echo urlencode($my_domains[$n]);?>" target="_blank">Go to site...</a>
                        <?php
                    }
                ?><br /></p>

        <?php
    }
?>

<input type="submit" value="Save" />
