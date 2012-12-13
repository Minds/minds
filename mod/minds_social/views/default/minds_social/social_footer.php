<?php 
	$og_url = get_input('og:url');
?>
<div class="minds-social">
	<div class="fb-like" data-href="<?php echo $og_url;?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial"></div>
	
	<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	
	<!-- Place this tag where you want the +1 button to render. -->
	<div class="g-plusone" data-size="medium"></div>
	
	
	<script type="text/javascript">
	  (function() {
	    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	    po.src = 'https://apis.google.com/js/plusone.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>
	
	<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
	<script type="IN/Share" data-counter="right"></script>
	
	<a href="http://www.reddit.com/submit" class='reddit-share' onclick="window.location = 'http://www.reddit.com/submit?url=' + encodeURIComponent(window.location); return false"> 
		<img src="http://www.reddit.com/static/spreddit7.gif" alt="submit to reddit" border="0" /> 
	</a>
	
</div>