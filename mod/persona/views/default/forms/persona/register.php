<h1>
    <?php echo elgg_echo('persona:details'); ?>
</h1>
<div class="mtm" >
    <p>
	<?php echo elgg_echo('persona:details:explanation'); ?>
    </p>
</div>
<div>
    <label><?php echo elgg_echo('persona:name'); ?></label><br />
    <span class="elgg-text-help"><?php echo elgg_echo('persona:name:explanation'); ?></span>
    <?php echo elgg_view('input/text',array('name' => 'name', 'pattern' => '.{2,}')); ?>
</div>
<div>
    <label><?php echo elgg_echo('persona:username'); ?></label><br />
    <span class="elgg-text-help"><?php echo elgg_echo('persona:username:explanation'); ?></span>
    <?php echo elgg_view('input/text',array('name' => 'username', 'pattern' => '[A-Za-z0-9]{4,128}')); ?>
</div>
<div>
    <?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
</div>