<?php
/*
 * Bootcamp renamed orientation- index view
 */


	

/*
 * Bootcamp renamed orientation- index view
 */
?>
<ul class='elgg-list'>
	
<?php
	foreach(orientation_get_steps() as $step){
		echo elgg_view('orientation/step', array('step'=>$step, 'number'=>$i));
		$i++;
	}
?>
</ul>
