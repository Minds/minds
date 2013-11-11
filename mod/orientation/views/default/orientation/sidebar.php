<?php
/*
 * Bootcamp renamed orientation- index view
 */
elgg_load_library('orientation');

if(orientation_calculate_progress() < 100){
	
	echo '<div class="orientation sidebar">';
	echo '<a href="'. elgg_get_site_url() . 'orientation"><h3>' . elgg_echo('orientation:sidebar:title') . '</h3></a>';
	
	$i = 1;
?>

<ul class='elgg-list'>

<?php

	foreach(orientation_get_steps() as $step){
		if(!$step->completed){
			echo elgg_view('orientation/step', array('step'=>$step, 'number'=>$i));
			$i++;
		}
	}
	
//	echo '</div>';
}

?>

</ul>
</div>
<div class="clearfix"></div>
