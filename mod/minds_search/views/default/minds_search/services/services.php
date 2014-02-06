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
//if($type == 'all'){
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
/*} elseif($type=='photo') {
	echo '<div class="minds-search minds-search-section minds-search-section-image">';
	echo '<h3> '. elgg_echo('minds_search:type:'.$type) . ' </h3>';
	
	foreach($data as $item){
		echo elgg_view('minds_search/services/types/image', array('photo'=>$item['_source']));
	}
		
	echo '</div>';
} elseif($type=='video'){
	echo '<div class="minds-search minds-search-section minds-search-section-video">';
	echo '<h3>'. 'Videos' . '</h3>';
	foreach($data as $item){
		echo elgg_view('minds_search/services/types/video', array('video'=>$item['_source']));
	}
	echo '</div>';
} elseif($type=='sound'){
	echo '<div class="minds-search minds-search-section minds-search-section-sound">';
	echo '<h3>'. 'Sounds' . '</h3>';
	foreach($data as $item){
		echo elgg_view('minds_search/services/types/sound', array('sound'=>$item['_source']));
	}
	echo '</div>';
} elseif($type=='article'){
	echo '<div class="minds-search minds-search-section minds-search-section-article">';
	echo '<h3>'. 'Articles & Wikis' . '</h3>';
	foreach($data as $item){
		echo elgg_view('minds_search/services/types/article', array('article'=>$item['_source']));
	}
	echo '</div>';
} elseif($type=='user'){
	echo '<div class="minds-search minds-search-section">';
	echo '<h3>'. 'Channels on Minds' . '</h3>';
	foreach($data as $item){
		echo elgg_view('minds_search/services/types/user', array('user'=>$item['_source']));
	}
	echo '</div>';
} elseif($type=='group'){
	echo '<div class="minds-search minds-search-section">';
	echo '<h3>'. 'Groups on Minds' . '</h3>';
	foreach($data as $item){
		echo elgg_view('minds_search/services/types/group', array('group'=>$item['_source']));
	}
	echo '</div>';
}*/
echo '</ul>';