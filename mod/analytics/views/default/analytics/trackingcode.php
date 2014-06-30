<?php

$tracking_id = elgg_get_plugin_setting('tracking_id', 'analytics'); 

if(!$tracking_id){
	return false;
}
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  <?php if (elgg_is_logged_in()){?>
  	ga('create', '<?php echo $tracking_id;?>', 'minds.com', {'userId': '<?php echo elgg_get_logged_in_user_guid(); ?>'});
  	ga('send', {
	  'hitType': 'event',          // Required.
	  'eventCategory': 'loggedin',   // Required.
	  'eventAction': 'pageview',      // Required.
	  'eventLabel': 'loggedin user',
	  'eventValue': 1
	});

   <?php }else{?>
        ga('create', '<?php echo $tracking_id;?>', 'minds.com');
  <?php } ?> 
  ga('send', 'pageview');

</script>

<?php return; ?>
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://analytics.minds.org/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();

</script>
<noscript><p><img src="http://analytics.minds.org/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
