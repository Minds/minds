<?php
/**
 * Main orientation view
 */

$step = $vars['step'];
$steps = $vars['steps'];

//$content = elgg_view('orientation/register/'.$step, $vars['vars']);
?>

<div class="orientation-register-wrapper">
	<div class="orientation-menu">
		<?php echo elgg_view('orientation/menu', array('steps'=>$steps, 'step'=>$step)); ?>
	</div>
	<div class="orientation-content">
		<form method="post" enctype="multipart/form-data">
			<?php echo elgg_view('orientation/register/'.$step, $vars['vars']); ?>
			<div class="orientation-action-buttons-wrapper">
				<input type="submit" name="skip" value="Skip" class="elgg-button elgg-button-action"/>
				<input type="submit" value="Next" class="elgg-button elgg-button-submit"/>
			</div>
		</form>
	</div>
	<div style="clear: both"></div>
</div>
