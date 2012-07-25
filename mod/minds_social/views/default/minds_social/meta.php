<?php
	
	echo "<meta property=\"fb:app_id\" content=\"184865748231073\" />"; 
	
	$og_type = get_input('og:type');
	$og_url = get_input('og:url');
	$og_title = get_input('og:title');
	$og_image = get_input('og:image');
	
	if($og_type)
 	echo "<meta property=\"og:type\"   content=\"$og_type\" />";
	if($og_url)
  	echo "<meta property=\"og:url\"    content=\"$og_url\" />";
	if($og_title)
  	echo "<meta property=\"og:title\"  content=\"$og_title\" />";
	if($og_image)
  	echo "<meta property=\"og:image\"  content=\"$og_image\" />";
	
?>
