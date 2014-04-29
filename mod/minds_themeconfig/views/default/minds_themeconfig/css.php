<?php global $CONFIG; ?>
<style type="text/css">

    <?php
    
	// Overrides fonts 
	foreach ($CONFIG->theme_fonts as $element => $code) {

	    $f = elgg_get_plugin_setting('font::' . $code, 'minds_themeconfig');
	    $s = elgg_get_plugin_setting('font_size::' . $code, 'minds_themeconfig');
	    $c = elgg_get_plugin_setting('font_colour::' . $code, 'minds_themeconfig');
	    
	    if ($f) {
		?>
<?php echo $code; ?> {
    font-family: <?php echo $f; ?>;
}
		<?php
	    } 
	    
	    if ($s) {		
		?>
<?php echo $code; ?> {
    font-size: <?php echo $s; ?>pt;
}
		<?php
	    }
	    
	    if ($c) {		
		?>
<?php echo $code; ?> {
    color: #<?php echo $c; ?>;
}
		<?php
	    }
	    
	}
	
    
    ?>
    
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
    
    <?php if ($topbar_colour = elgg_get_plugin_setting('topbar_colour', 'minds_themeconfig')) { ?>
    .hero > .topbar {
        background-color: #<?php echo $topbar_colour;?>;
    }
    <?php } ?>
    
    <?php if ($sidebar_colour = elgg_get_plugin_setting('sidebar_colour', 'minds_themeconfig')) { ?>
    .elgg-sidebar {
        background-color: #<?php echo $sidebar_colour;?>;
    }
    <?php } ?>
    
    <?php if ($main_colour = elgg_get_plugin_setting('main_colour', 'minds_themeconfig')) { ?>
    .elgg-main {
        background-color: #<?php echo $main_colour;?>;
    }
    .elgg-main > .elgg-content {
        background-color: #<?php echo $main_colour;?>;
    }
    
    .elgg-main > .minds-comments-form {
        background-color: #<?php echo $main_colour;?>;
    }
    <?php } ?>
    
    <?php if (elgg_get_plugin_setting('background_override', 'minds_themeconfig') == 'true') { ?>
    body {
        background-image:url('<?php echo elgg_get_site_url(); ?>themeicons/background/<?php echo elgg_get_plugin_setting('background_override_ts', 'minds_themeconfig'); ?>.png');
    }
    <?php } ?>
    
    <?php if ($text_colour = elgg_get_plugin_setting('text_colour', 'minds_themeconfig')) { ?>
    body {
        color: #<?php echo $text_colour;?>;
    }
    <?php } ?>
    
    <?php echo htmlspecialchars_decode(elgg_get_plugin_setting('custom_css', 'minds_themeconfig')); ?>
</style>
				
