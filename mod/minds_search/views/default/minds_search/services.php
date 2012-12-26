<?php
/**
 * Minds Search Service Listing
 *
 * @package minds_search
 */
 
$data = $vars['data'];

$photos = $data['photos'];
shuffle($photos);
if($photos){
	echo '<div class="minds-search minds-search-section minds-search-section-image">';
	echo '<h3>'. 'Photos' . '</h3>';
	foreach($photos as $photo){
		echo elgg_view('minds_search/service/image', array('photo'=>$photo));
	}
	echo '</div>';
}

$videos = $data['videos'];
shuffle($videos);
if($videos){
	echo '<div class="minds-search minds-search-section minds-search-section-video">';
	echo '<h3>'. 'Videos' . '</h3>';
	foreach($videos as $video){
		echo elgg_view('minds_search/service/video', array('video'=>$video));
	}
	echo '</div>';
}


$sounds = $data['sounds'];
shuffle($sounds);
if($sounds){
	echo '<div class="minds-search minds-search-section minds-search-section-sound">';
	echo '<h3>'. 'Sounds' . '</h3>';
	foreach($sounds as $sound){
		echo elgg_view('minds_search/service/video', array('video'=>$sound));
	}
	echo '</div>';
}