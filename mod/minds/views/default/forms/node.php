<?php

//TODO: Validation

    global $CONFIG;
    
    $ROOT_DOMAIN = 'minds.com';
    
    // Should probably cache this.
    $domains = json_decode(file_get_contents($CONFIG->multisite_endpoint . 'webservices/get_user_domains.php?minds_user_id=' .$vars['minds_user_guid']));
    $my_domains = $domains->domains;
?>
<input type="hidden" name="minds_user_id" value="<?=$vars['minds_user_guid'];?>" />

<?php
    for ($n = 0; $n < 10; $n++) {
        ?>

                <p><input type="text" name="domains[]" placeholder="e.g. foo.<?=$ROOT_DOMAIN; ?>" value="<?= $my_domains[$n];?>" /> <?php
                    if ($my_domains[$n]) {
                        ?>
                            <a href="http://<?=$my_domains[$n];?>/install.php" target="_blank">Run Installer...</a>
                        <?php
                    }
                ?><br /></p>

        <?php
    }
?>

<input type="submit" value="Save" />