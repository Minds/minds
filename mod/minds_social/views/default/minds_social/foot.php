<?php
        global $SOCIAL_META_TAGS;
        $og_url = $SOCIAL_META_TAGS['og:url']['content'];

	if(!isset($og_url)){
		return;
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
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>                  


<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>
<script type="text/javascript">stLight.options({publisher: "7b9c530d-60a6-4696-8812-fdb47d887122", doNotHash: false, doNotCopy: false, hashAddressBar: false, st_url: "<?php echo $og_url; ?>"});</script> 

<script>
var options={ "publisher": "7b9c530d-60a6-4696-8812-fdb47d887122", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["reddit", "facebook", "twitter", "googleplus", "linkedin", "pinterest"]}};
var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
</script>
