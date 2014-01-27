<style type="text/css">
    
    <?php if ($h2_font = elgg_get_plugin_setting('h2_font', 'minds_themeconfig')){ ?>
	.minds-body-header h2{
		font-family:<?php echo $h2_font;?>;
	}
    <?php } ?>

    <?php if ($background_colour = elgg_get_plugin_setting('background_colour', 'minds_themeconfig')) { ?>
    body {
        background-color: #<?php echo $background_colour;?>;
    }
    <?php } ?>
    
    <?php if (elgg_get_plugin_setting('background_override', 'minds_themeconfig') == 'true') { ?>
    body {
        background-image:url('<?php echo elgg_get_site_url(); ?>themeicons/background');
    }
    <?php } ?>
    
    <?php if ($text_colour = elgg_get_plugin_setting('text_colour', 'minds_themeconfig')) { ?>
    body {
        color: #<?php echo $text_colour;?>;
    }
    <?php } ?>
    
    <?php echo htmlspecialchars_decode(elgg_get_plugin_setting('custom_css', 'minds_themeconfig')); ?>
</style>
				
