<?php
	
	
	global $SOCIAL_META_TAGS;
	
	foreach($SOCIAL_META_TAGS as $tag){
		if (strpos($tag['property'],'og') !== false || strpos($tag['property'],'fb') !== false) {
			echo "\t<meta property=\"" . $tag['property'] ."\" content=\"" . $tag['content'] ."\" /> \n";
		} else {
			 echo "\t<meta name=\"" . $tag['property'] ."\" content=\"" . $tag['content'] ."\" /> \n";
		}
	}
	
