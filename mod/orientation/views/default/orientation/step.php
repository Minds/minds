<?php
/*
 * Bootcamp renamed orienation - step view
 */
$step = $vars['step'];
$number = $vars['number'];
?>
<a href='<?php echo $step->href;?>'>
	<div class='step <?php echo $step->completed ? 'completed' :'';?>'>
		<div class='number'>
			<?php echo $step->completed ? '&#x2713' : $number;?> 
		</div>
		<div class='inner'>
			<h3><?php echo $step->title;?></h3>
			<div><p><?php echo $step->content;?></p></div>
		</div>
	</div>
</a>
