<?php
/*
 * Bootcamp - index view
 */

echo '<div class="progress"><h3>' . bootcamp_calculate_progress() . '%</h3><p>'.elgg_echo('bootcamp:progress:blurb') . '</p></div>';
echo '<div><p>' . elgg_echo('bootcamp:blurb') . '</p></div>';

$i = 1;
foreach(bootcamp_get_steps() as $step){
	echo elgg_view('bootcamp/step', array('step'=>$step, 'number'=>$i));
	$i++;
}

