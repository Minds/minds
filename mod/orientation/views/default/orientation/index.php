<?php
/*
 * Bootcamp renamed orientation- index view
 */


	

/*
 * Bootcamp renamed orientation- index view
 */
echo '<div class="progress"><h3>' . orientation_calculate_progress() . '%</h3><p>'.elgg_echo('orientation:progress:blurb') . '</p></div>';
echo '<div class="blurb"><p>' . elgg_echo('orientation:blurb') . '</p></div>';

	$i = 1;
?>

<ul class='elgg-list'>
	
<?php
	foreach(orientation_get_steps() as $step){
		echo elgg_view('orientation/step', array('step'=>$step, 'number'=>$i));
		$i++;
	}
?>
</ul>
