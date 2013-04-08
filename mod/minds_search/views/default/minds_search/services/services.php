<?php
/**
 * Minds Search Service Listing
 *
 * @package minds_search
 */
 
$data = $vars['data'];
//var_dump($data);
$type = get_input('type', 'all');

if($type == 'all'){
	foreach($data as $item){
		if($item['_type'] == 'photo')
			echo elgg_view('minds_search/services/types/image', array('photo'=>$item['_source']));
		if($item['_type'] == 'video')
			echo elgg_view('minds_search/services/types/video', array('video'=>$item['_source']));
		if($item['_type'] == 'sound')
			echo elgg_view('minds_search/services/types/sound', array('sound'=>$item['_source']));
		if($item['_type'] == 'article') 
			echo elgg_view('minds_search/services/types/article', array('article'=>$item['_source']));
		if($item['_type'] == 'user') 
			echo elgg_view('minds_search/services/types/user', array('user'=>$item['_source']));
		if($item['_type'] == 'group') 
			echo elgg_view('minds_search/services/types/group', array('group'=>$item['_source']));
	}
} elseif($type=='photo') {
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
}