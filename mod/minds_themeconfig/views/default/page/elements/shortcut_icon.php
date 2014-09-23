<?php
if ($ts = elgg_get_plugin_setting('logo_favicon_ts','minds_themeconfig')) {
?>
<link rel="icon" type="image/jpeg" href="<?php echo elgg_get_site_url(); ?>themeicons/logo_favicon/<?= $ts ?>.jpg" />
<?php
}
else
{
?>
<link rel="icon" type="image/png" href="<?php echo elgg_get_site_url();?>_graphics/icon.png" />
<?php
}
?>
