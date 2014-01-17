<?php
	
	
	global $SOCIAL_META_TAGS;
	
	foreach($SOCIAL_META_TAGS as $tag){
		echo "\t<meta property=\"" . $tag['property'] ."\" content=\"" . $tag['content'] ."\" /> \n";
	}
	
