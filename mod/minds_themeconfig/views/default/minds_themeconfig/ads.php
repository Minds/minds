<?php

if(elgg_get_plugin_setting('enabled', 'minds_themeconfig') == 'off'){
	return false;
}

$domain = elgg_get_site_url();
switch($vars['type']){
	case 'content-side':
	case 'content-side-single':
		if($ad = elgg_get_plugin_setting('ads-side-1', 'minds_themeconfig') && strlen(elgg_get_plugin_setting('ads-side-1', 'minds_themeconfig')) > 5){
			echo $ad;
		} else {
			echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- Minds large block -->
		<ins class="adsbygoogle"
		     style="display:inline-block;width:336px;height:280px"
		     data-ad-client="ca-pub-9303771378013875"
		     data-ad-slot="5788264423"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>';
			echo "<div id='taboola-right-rail-thumbs-3rd-mix'></div>
		<script type=\"text/javascript\">

		    window._taboola = window._taboola || [];
		    _taboola.push({mode:'thybrid-thumbs-2r-rr', container:'taboola-right-rail-thumbs-3rd-mix', placement:'$domain-rr1', target_type:'mix'});

		</script>";	
		}

                break;
	case 'content-side-single-user-2':
		echo elgg_get_plugin_setting('ads-side-2', 'minds_themeconfig');
		break;
	case 'content-below-banner':
		echo elgg_get_plugin_setting('ads-content-1', 'minds_themeconfig');
		break;
	case 'content-foot-user-1':	
	case 'content-foot':
	case 'content-block-rotator':
                //echo elgg_get_plugin_setting('ads-content-2', 'minds_themeconfig');
               	echo "<div id='taboola-below-main-column'></div>
<script type='text/javascript'>

window._taboola = window._taboola || [];

_taboola.push({mode:'thumbs-2r',
container:'taboola-below-main-column',
placement:'below-main-column'});

</script>


<div id='taboola-text-2-columns-mix'></div>
<script type='text/javascript'>

	window._taboola = window._taboola || [];

	_taboola.push({mode:'text-links-2c', container:'taboola-text-2-columns-mix', placement:'$domain', target_type:'mix'}); </script>";

		 break;
}
