<?php
if (isset($vars['class'])) {
        $vars['class'] = "elgg-input-dragbox {$vars['class']}";
} else {
        $vars['class'] = "elgg-input-dragbox";
}

$defaults = array(
        'disabled' => false,
		'name' => 'dragbox',
		'options' => array(), //unselected 
	    'selected' => array()  //selected
);

$vars = array_merge($defaults, $vars);
?>
<div class="<?php echo $vars['class']; ?>">
	<div class="selected">
		<span></span>
		<?php foreach($vars['selected'] as $value => $view){ ?>
			<span>
				<input type="hidden" value="<?php echo $value;?>" name="<?php echo $vars['name'];?>[]">
				<?php echo $view; ?>
			</span>
		<?php } ?>
	</div>
	<div class="not-selected">
		<div class = "drag-prompt">
			drag icons
		</div>
		<?php foreach($vars['options'] as $value => $view){ ?>
			<span>
				<input type="hidden" value="<?php echo $value;?>" name="_<?php echo $vars['name'];?>[]">
				<?php echo $view; ?>
			</span>
		<?php } ?>
	</div>
</div>