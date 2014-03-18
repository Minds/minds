<?php



if (elgg_get_plugin_setting('logo_override', 'minds_themeconfig')) {
    ?>
<link rel="icon" type="image/jpeg" href="<?php echo elgg_get_site_url(); ?>themeicons/logo_favicon/<?php echo elgg_get_plugin_setting('logo_favicon_ts', 'minds_themeconfig'); ?>.jpg" />
<?php
}
else
{
?>
<link rel="SHORTCUT ICON" href="<?php echo elgg_get_site_url(); ?>mod/minds/graphics/favicon.ico" />
<?php
}
?>
