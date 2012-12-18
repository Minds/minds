<?php
/*
 * Bootcamp - index view
 */
elgg_load_library('bootcamp');

if(bootcamp_calculate_progress() < 100){
	
	echo '<div class="bootcamp sidebar">';
	echo '<h3>' . elgg_echo('bootcamp:title') . '</h3>';
	
	$i = 1;
	foreach(bootcamp_get_steps() as $step){
		if(!$step->completed){
			echo elgg_view('bootcamp/step', array('step'=>$step, 'number'=>$i));
			$i++;
		}
	}
	
	echo '</div>';
}
