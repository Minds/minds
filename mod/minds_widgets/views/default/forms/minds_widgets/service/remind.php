<label>
    Title: <br />
    <?php echo elgg_view('input/text', array('name' => 'title', 'value' => get_input('title'), 'placeholder' => 'Title')); ?>
</label>
<label>
    Description: <br />
    <?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => get_input('description'), 'placeholder' => 'Short description')); ?>
</label>
<?php echo elgg_view('input/hidden', array('name' => 'url', 'value' => get_input('url')));?>
<?php echo elgg_view('input/submit', array('value' => 'reMind'));?>