<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:frontpagetext');?>:<br />
        <?php echo elgg_view('input/text', array('name' => 'frontpagetext', 'value' => elgg_get_plugin_setting('frontpagetext', 'minds_themeconfig'))); ?>
</label></p>

<hr />

<p><label>
    <?php echo elgg_echo('minds_themeconfig:logo');?>:<br />
        <?php echo elgg_view('input/file', array('name' => 'logo')); ?>
    </label> <br /><label><input type="checkbox" name="logo_remove" value="y" /> <?php echo elgg_echo('remove'); ?></label></p>
<p><label>
    <?php echo elgg_echo('minds_themeconfig:favicon');?>:<br />
        <?php echo elgg_view('input/file', array('name' => 'favicon')); ?>
</label> <br /><label><input type="checkbox" name="favicon_remove" value="y" /> <?php echo elgg_echo('remove'); ?></label></p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:background');?>:<br />
        <?php echo elgg_view('input/file', array('name' => 'background')); ?>
</label> <br /><label><input type="checkbox" name="background_remove" value="y" /> <?php echo elgg_echo('remove'); ?></label></p>

<hr />

<p><label>
    <?php echo elgg_echo('minds_themeconfig:topbar:colour');?>:<br />
        <?php echo elgg_view('input/colourpicker', array('name' => 'topbar_colour', 'class' => 'pick-colour', 'style' => "background-color:#" .elgg_get_plugin_setting('topbar_colour', 'minds_themeconfig') . ";", 'value' => elgg_get_plugin_setting('topbar_colour', 'minds_themeconfig'))); ?>
</label> </p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:carousel:colour');?>:<br />
        <?php echo elgg_view('input/colourpicker', array('name' => 'carousel_colour', 'class' => 'pick-colour', 'style' => "background-color:#" .elgg_get_plugin_setting('carousel_colour', 'minds_themeconfig') . ";", 'value' => elgg_get_plugin_setting('carousel_colour', 'minds_themeconfig'))); ?>
</label> </p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:background:colour');?>:<br />
        <?php echo elgg_view('input/colourpicker', array('name' => 'background_colour', 'class' => 'pick-colour', 'style' => "background-color:#" .elgg_get_plugin_setting('background_colour', 'minds_themeconfig') . ";", 'value' => elgg_get_plugin_setting('background_colour', 'minds_themeconfig'))); ?>
</label> </p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:sidebar:colour');?>:<br />
        <?php echo elgg_view('input/colourpicker', array('name' => 'sidebar_colour', 'class' => 'pick-colour', 'style' => "background-color:#" .elgg_get_plugin_setting('sidebar_colour', 'minds_themeconfig') . ";", 'value' => elgg_get_plugin_setting('sidebar_colour', 'minds_themeconfig'))); ?>
</label> </p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:main:colour');?>:<br />
        <?php echo elgg_view('input/colourpicker', array('name' => 'main_colour', 'class' => 'pick-colour', 'style' => "background-color:#" .elgg_get_plugin_setting('main_colour', 'minds_themeconfig') . ";", 'value' => elgg_get_plugin_setting('main_colour', 'minds_themeconfig'))); ?>
</label> </p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:button:colour');?>:<br />
        <?php echo elgg_view('input/colourpicker', array('name' => 'button_colour', 'class' => 'pick-colour', 'style' => "background-color:#" .elgg_get_plugin_setting('button_colour', 'minds_themeconfig') . ";", 'value' => elgg_get_plugin_setting('button_colour', 'minds_themeconfig'))); ?>
</label> </p>

<?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>

<script>
    $('.pick-colour').ColorPicker({
	    onSubmit: function(hsb, hex, rgb, el) {
		
		var sample = $(el).attr('data-sample');
		
		$(el).css("background-color", "#" + hex);
	
		$(el).val(hex);
		$(el).ColorPickerHide();
		
		
	    },
	    onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	    }
	});
</script>