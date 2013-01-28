

<div>
    <label><?php echo elgg_echo('oauth2:name:label'); ?></label>
    <?php echo elgg_view('input/text', array('name' => 'name', 'value' => $vars['entity']->title)); ?>
</div>

<div>
    <label><?php echo elgg_echo('oauth2:url:label'); ?></label>
    <?php echo elgg_view('input/text', array('name' => 'url', 'value' => $vars['entity']->description)); ?>
</div>

<div>
    <?php echo elgg_view('input/submit'); ?>
</div>

