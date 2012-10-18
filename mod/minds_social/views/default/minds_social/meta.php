<?php
	
	echo "<meta property=\"fb:app_id\" content=\"184865748231073\" />"; 
	
	$og_type = get_input('og:type');
	$og_url = get_input('og:url');
	$og_title = get_input('og:title');
	$og_description = get_input('og:description');
	$og_image = get_input('og:image');
	$og_video = get_input('og:video');
	$og_video_secure = get_input('og:video:secure_url');
	$og_video_type = get_input('og:video:type');
	$og_video_width = get_input('og:video:width');
	$og_video_height = get_input('og:video:height');
	
	if($og_type)
 	echo "<meta property=\"og:type\"   content=\"$og_type\" />";
	if($og_url)
  	echo "<meta property=\"og:url\"    content=\"$og_url\" />";
	if($og_description)
  	echo "<meta property=\"og:description\"  content=\"$og_description\" />";
	if($og_title)
  	echo "<meta property=\"og:title\"  content=\"$og_title\" />";
	if($og_image)
  	echo "<meta property=\"og:image\"  content=\"$og_image\" />";
	if($og_video)
	echo "<meta property=\"og:video\" content=\"$og_video\">";
	if($og_video_secure)
	echo "<meta property=\"og:video:secure_url\" content=\"$og_video_secure\">";
	if($og_video_type)
    echo "<meta property=\"og:video:type\" content=\"$og_video_type\">";
    if($og_video_width)
	echo "<meta property=\"og:video:width\" content=\"$og_video_width\">";
	if($og_video_height)
    echo "<meta property=\"og:video:height\" content=\"$og_video_height\">";
	
    echo "<meta property=\"og:site_name\" content=\"Minds, Freedom to share\">";
	
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=184865748231073";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
