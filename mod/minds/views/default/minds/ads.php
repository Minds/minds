<?php

$type = elgg_extract('type', $vars, 'content-side');

if($type == 'content-side'){
	if(elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds')){
		echo elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds');
	} else {
	echo '<script type="text/javascript"><!--
                google_ad_client = "ca-pub-9303771378013875";
                /* Minds large block */
                google_ad_slot = "5788264423";
                google_ad_width = 336;
                google_ad_height = 280;
                //-->
                </script>
                <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script>';
	}
	echo '<script type="text/javascript"><!--
		google_ad_client = "ca-pub-9303771378013875";
		/* Minds large block */
		google_ad_slot = "5788264423";
		google_ad_width = 336;
		google_ad_height = 280;
		//-->
		</script>
		<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>';
} elseif ($type == 'content-side-single'){
  echo '<script type="text/javascript"><!--
                google_ad_client = "ca-pub-9303771378013875";
                /* Minds large block */
                google_ad_slot = "5788264423";
                google_ad_width = 336;
                google_ad_height = 280;
                //-->
                </script>
                <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script>';

} elseif($type == 'content-foot'){
/*	echo '<script id="mNCC" language="javascript">  
			medianet_width="728";  
			medianet_height= "90";  
			medianet_crid="451826483";  
		 </script> 
		 <script id="mNSC" src="http://contextual.media.net/nmedianet.js?cid=8CU21QO2U" language="javascript"></script>';
*/
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
			
} elseif($type == 'news'){
 echo '<script type="text/javascript"><!--
google_ad_client = "ca-pub-9303771378013875";
/* News */
google_ad_slot = "6842535224";
google_ad_width = 300;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
	
} elseif($type == 'large-block'){
	echo '<script type="text/javascript"><!--
google_ad_client = "ca-pub-9303771378013875";
/* Large ad */
google_ad_slot = "1083059623";
google_ad_width = 300;
google_ad_height = 600;
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
} elseif($type == 'adblade'){
	echo '<div class="adblade"><script type="text/javascript"><!--
	  adblade_cid="3144-3659842753";
	  adblade_ad_width="600";
	  adblade_ad_height="450";
	  adblade_ad_host="web.adblade.com";
	//--></script>
	<script type="text/javascript" src="http://web.adblade.com/js/ads/show.js"></script></div>';
}

