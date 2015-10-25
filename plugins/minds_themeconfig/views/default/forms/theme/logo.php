<?php

?>

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


<?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
