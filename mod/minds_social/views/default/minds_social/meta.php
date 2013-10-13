<?php
	
	
	global $SOCIAL_META_TAGS;
	
	foreach($SOCIAL_META_TAGS as $tag){
		echo "\t<meta property=\"" . $tag['property'] ."\" content=\"" . $tag['content'] ."\" /> \n";
	}
	
?>
	 <div id="fb-root"></div>
	<script>(function(d, s, id) {
 				 var js, fjs = d.getElementsByTagName(s)[0];
 				 if (d.getElementById(id)) return;
  					js = d.createElement(s); js.id = id;
 			 		js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=184865748231073";
 					 fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
	</script>
