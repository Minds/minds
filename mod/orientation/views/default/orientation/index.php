<?php
/*
 * Bootcamp renamed orientation- index view
 */

echo '<div class="progress"><h3>' . orientation_calculate_progress() . '%</h3><p>'.elgg_echo('orientation:progress:blurb') . '</p></div>';
echo '<div><p>' . elgg_echo('orientation:blurb') . '</p></div>';

$i = 1;
foreach(orientation_get_steps() as $step){
	echo elgg_view('orientation/step', array('step'=>$step, 'number'=>$i));
	$i++;
}

