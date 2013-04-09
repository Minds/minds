<?php

$type = elgg_extract('type', $vars, 'content-side');

if($type == 'content-side'){
	if(elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds')){
		echo elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds');
	} else {
		echo '<script type="text/javascript"><!--
				google_ad_client = "ca-pub-9303771378013875";
				/* Minds Content Sidebar */
				google_ad_slot = "6555161626";
				google_ad_width = 200;
				google_ad_height = 200;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>';
	}
	echo '<script type="text/javascript"><!--
			google_ad_client = "ca-pub-9303771378013875";
			/* Minds Content Sidebar */
			google_ad_slot = "6555161626";
			google_ad_width = 200;
			google_ad_height = 200;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>';
} elseif($type == 'content-foot'){
	echo '<script type="text/javascript"><!--
			google_ad_client = "ca-pub-9303771378013875";
			/* Content Bottom Banner */
			google_ad_slot = "9810862421";
			google_ad_width = 728;
			google_ad_height = 90;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>';
} elseif($type == 'news-side'){
	echo '<div style="margin:8px;"><script type="text/javascript"><!--
			google_ad_client = "ca-pub-9303771378013875";
			/* News ad */
			google_ad_slot = "9083688828";
			google_ad_width = 125;
			google_ad_height = 125;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script></div>';
} elseif($type == 'large-block'){
	echo '<script type="text/javascript"><!--
			google_ad_client = "ca-pub-9303771378013875";
			/* Minds Large Wiki Square */
			google_ad_slot = "8814173620";
			google_ad_width = 336;
			google_ad_height = 280;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>';
} elseif($type == 'small-banner'){
	echo '<script type="text/javascript"><!--
			google_ad_client = "ca-pub-9303771378013875";
			/* Minds Small Banner WIKI */
			google_ad_slot = "4244373227";
			google_ad_width = 234;
			google_ad_height = 60;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>';
}
elseif($type == 'search-ad'){
	echo '<script type="text/javascript"><!--
			google_ad_client = "ca-pub-9303771378013875";
			/* Search ad, square */
			google_ad_slot = "1151306021";
			google_ad_width = 125;
			google_ad_height = 125;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>';
}

