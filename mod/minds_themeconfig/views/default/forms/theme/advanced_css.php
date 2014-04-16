
<p><label>
    <?php echo elgg_echo('minds_themeconfig:custom_css');?>:<br />
    <textarea name="custom_css" rows="10"><?php echo elgg_get_plugin_setting('custom_css', 'minds_themeconfig'); ?></textarea>
</label></p>
<?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
