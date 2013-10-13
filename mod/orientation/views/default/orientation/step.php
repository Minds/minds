<?php
/*
 * Bootcamp renamed orienation - step view
 */
$step = $vars['step'];
$number = $vars['number'];
?>
<li class="elgg-item">
<a href='<?php echo $step->href;?>'>
	<div class='step <?php echo $step->completed ? 'completed' :'';?>'>
			<?php if($step->completed){ ?>
			<div class='tick'>
				&#10003;
			</div>
			<?php } ?>
			<div class='number'>
				<?php echo $step->completed ? '&#x2713' : $number;?> 
			</div>
						<span class="entypo"><?php echo $step->icon;?></span>
				<div class='inner'>
					<h3><?php echo $step->title;?></h3>
				</div>
					<div class='content'><p><?php echo $step->content;?></p></div>
							
		</div>
</a>
</li>
