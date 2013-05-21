<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:logo');?>:<br />
        <?php echo elgg_view('input/file', array('name' => 'logo')); ?>
</label></p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:backgroundcolour');?>:<br />
        <?php echo elgg_view('input/colourpicker', array('name' => 'background_colour', 'placeholder' => 'e.g. ffffff', 'value' => elgg_get_plugin_setting('background_colour', 'minds_themeconfig'))); ?>
</label></p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:textcolour');?>:<br />
        <?php echo elgg_view('input/colourpicker', array('name' => 'text_colour', 'placeholder' => 'e.g. ffffff', 'value' => elgg_get_plugin_setting('text_colour', 'minds_themeconfig'))); ?>
</label></p>

<?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>