<?php



if (elgg_get_plugin_setting('logo_override', 'minds_themeconfig')) {
    ?>
<link rel="icon" type="image/jpeg" href="<?= elgg_get_site_url(); ?>themeicons/logo_favicon" />
<?php
}
else
{
?>
<link rel="SHORTCUT ICON" href="<?php echo elgg_get_site_url(); ?>_graphics/favicon.ico" />
<?php
}
?>