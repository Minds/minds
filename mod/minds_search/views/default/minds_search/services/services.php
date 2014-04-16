<?php
/**
 * Minds Search Service Listing
 *
 * @package minds_search
 */
 
$data = $vars['data'];
//var_dump($data);
$type = get_input('type', 'all');

$ad[1] = rand(0,3);
$ad[2] = rand(8,12);
$ad[3] = rand(18,29);

echo '<ul class="elgg-list minds-search-list mason x4">';
	$i = 0;
	foreach($data as $item){
		$type = $item['_type'];
		echo "<li class='elgg-item item minds-search-item '>";
			if(elgg_view_exists("minds_search/services/types/$type")){ 
				echo elgg_view("minds_search/services/types/$type", array('source'=>$item['_source']));
			} else { 
				echo elgg_view("minds_search/services/types/default", array('source'=>$item['_source']));
			}
		echo "</li>";
		
		if($i==$ad[1] || $i==$ad[2] || $i == $ad[3]){
		//	echo elgg_view('minds_search/services/types/ad');
		}
		$i++;
	}
echo '</ul>';
