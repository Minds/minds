<?php
	$mime = $vars['entity']->getMimeType();

	if (elgg_view_exists('object/hjfile/specialcontent/' . $mime)) {
		$content = elgg_view('object/hjfile/specialcontent/' . $mime, $vars);
	} else if (elgg_view_exists("object/hjfile/specialcontent/" . substr($mime,0,strpos($mime,'/')) . "/default")) {
		$content = elgg_view("object/hjfile/specialcontent/" . substr($mime,0,strpos($mime,'/')) . "/default", $vars);
	}
	
	if (empty($content) || substr_count(strtolower($content),'<embed') || substr_count(strtolower($content),'<object') || substr_count(strtolower($content),'<script')) {
		echo elgg_view('object/default/embed',$vars);
	} else {
		echo $content;
	}
	
?>