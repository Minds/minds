<?php

$networks = minds_config_social_links();
$sitename = elgg_get_site_entity()->name;
?>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:copyright');?><br />
</label>
<?php echo elgg_view('input/text',array('input/text', 'name'=> 'copyright', 'value' => elgg_get_plugin_setting('copyright', 'minds_themeconfig'), 'placeholder'=>"Copyright &copy; $sitename 2014" )); ?>
</p>

<p><label>
    <?php echo elgg_echo('minds_themeconfig:networks');?><br />
</label>
</p>

<?php foreach($networks as $network => $n): ?>
	<p><label><?php echo $network;?></label> <?php echo elgg_view('input/text',array('input/text', 'name'=> "networks[$network]", 'value' => $n['url'])); ?>
<?php endforeach; ?>

<?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
