<?php

$type = elgg_extract('type', $vars, 'content-side');

if($type == 'content-side'){
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
}
