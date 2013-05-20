<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<p><label>
    <?=elgg_echo('minds_themeconfig:logo');?>:<br />
        <?=elgg_view('input/file', array('name' => 'logo')); ?>
</label></p>

<p><label>
    <?=elgg_echo('minds_themeconfig:backgroundcolour');?>:<br />
        <?=elgg_view('input/colourpicker', array('name' => 'background_colour', 'value' => elgg_get_plugin_setting('background_colour', 'minds_themeconfig'))); ?>
</label></p>

<?=elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>