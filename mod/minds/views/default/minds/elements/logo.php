<?php

if (elgg_get_plugin_setting('logo_override', 'minds_themeconfig')) { 
    ?>
<div class="logo">
    <img src="<?php echo elgg_get_site_url(); ?>themeicons/logo_main/<?php echo elgg_get_plugin_setting('logo_override_ts', 'minds_themeconfig'); ?>.png" />
    </div>
<?php
} else {

$img_src = elgg_get_site_url() == 'http://www.minds.com/' ? elgg_get_site_url().'mod/minds/graphics/minds_logo.png' : elgg_get_site_url().'mod/minds/graphics/minds_logo_io.png';
?>

<div class="logo">
        <img src="<?php echo $img_src;?>" width="200" height="90" />
    </div>

<?php } ?>