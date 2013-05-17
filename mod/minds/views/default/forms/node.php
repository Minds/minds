<?php

    global $CONFIG;
    
    $ROOT_DOMAIN = 'minds.com';
    
    // Should probably cache this.
    $domains = file_get_contents($CONFIG->multisite_endpoint . 'webservices/get_user_domains.php?minds_user_id=' .$vars['minds_user_guid']);
    $my_domains = $domains['list'];
?>
<input type="hidden" name="minds_user_id" value="<?=$vars['minds_user_guid'];?>" />

<?php
    for ($n = 0; $n < 10; $n++) {
        ?>

                <input type="text" name="domains[]" placeholder="e.g. foo.<?=$ROOT_DOMAIN; ?>" value="<?= $my_domains[$n]->domain;?>" /><br />

        <?php
    }
?>

<input type="submit" value="Save" />