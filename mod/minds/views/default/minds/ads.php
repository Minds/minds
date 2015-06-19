<?php
return true;
$type = elgg_extract('type', $vars, 'content-side');
switch($type){
	case 'responsive':
		echo '<div class="responsive-ad" style="display:none;"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- responsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9303771378013875"
     data-ad-slot="7588308825"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script></div>';
		break;
	case 'responsive-content':
		echo '<div class="responsive-ad responsive-ad-content" style="float:'.$vars['float'].';height:'.$vars['height'].';width:'. $vars['width'] .';"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- responsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9303771378013875"
     data-ad-slot="7588308825"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script></div>';
		break;
	case 'content-side':	
		//if(elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds')){
		//	echo elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds');
		//} else {
			echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Minds large block -->
<ins class="adsbygoogle"
     style="display:inline-block;width:336px;height:280px"
     data-ad-client="ca-pub-9303771378013875"
     data-ad-slot="5788264423"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
		//}
			echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Minds large block -->
<ins class="adsbygoogle"
     style="display:inline-block;width:336px;height:280px"
     data-ad-client="ca-pub-9303771378013875"
     data-ad-slot="5788264423"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
	break;
	case 'content-side-single':
		echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Minds large block -->
<ins class="adsbygoogle"
     style="display:inline-block;width:336px;height:280px"
     data-ad-client="ca-pub-9303771378013875"
     data-ad-slot="5788264423"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
	break;
	case 'content-side-single-user':
		if(elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds') && false){
               		//echo elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds');
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
        	}
	break;
	case 'content-side-single-user-2':
		if(elgg_get_plugin_user_setting('adblock2', elgg_get_page_owner_guid(), 'minds') && false){
                //	echo elgg_get_plugin_user_setting('adblock2', elgg_get_page_owner_guid(), 'minds');
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
	/*	echo "<script id=\"mNCC\" language=\"javascript\">  medianet_width='336';  medianet_height= '280';  medianet_crid='637466202';  </script>  <script id=\"mNSC\" src=\"//contextual.media.net/nmedianet.js?cid=8CU21QO2U\" language=\"javascript\"></script>";
        */	}
	break;
	case 'content-below-banner':
		echo '<div class="banner-ad"><div class="inner">';
        	if(elgg_get_plugin_user_setting('adblock3', elgg_get_page_owner_guid(), 'minds')){
                	echo elgg_get_plugin_user_setting('adblock3', elgg_get_page_owner_guid(), 'minds');
        	} else {
              /*  	echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Page Top Banner -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9303771378013875"
     data-ad-slot="9810862421"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';*/
//	echo "<script id=\"mNCC\" language=\"javascript\">  medianet_width='600';  medianet_height= '250';  medianet_crid='266435425';  </script>  <script id=\"mNSC\" src=\"http://contextual.media.net/nmedianet.js?cid=8CU21QO2U\" language=\"javascript\"></script> ";
        	}
		echo '</div></div>';
	break;
	case 'content-foot-user-1':
		if(elgg_get_plugin_user_setting('adblock3', elgg_get_page_owner_guid(), 'minds')){
                	//echo elgg_get_plugin_user_setting('adblock3', elgg_get_page_owner_guid(), 'minds');
        	} else {
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
		}
	break;
	case 'content-foot':
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
	break;
	case 'linkad-box':
		echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- linkad-box -->
		<ins class="adsbygoogle"
     		style="display:inline-block;width:200px;height:90px"
     		data-ad-client="ca-pub-9303771378013875"
    		 data-ad-slot="7254333223"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
	</script>';
	break;		
	case 'large-block':
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
	break;
	case 'small-banner':
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
	case 'search-ad':
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
//bellow content rotator
	break;
	case 'content-block-rotator':
		$providers = array(	'contentad' => '<div class="contentad"><div id="contentad9733"></div>
	<script type="text/javascript">
    (function() {
        var params =
        {
            id: "67c8c761-6755-4867-92be-6317f1ea173a",
            d:  "bWluZHMuY29t",
            wid: "9733",
            cb: (new Date()).getTime()
        };

        var qs="";
        for(var key in params){qs+=key+"="+params[key]+"&"}
        qs=qs.substring(0,qs.length-1);
        var s = document.createElement("script");
        s.type= "text/javascript";
        s.src = "//api.content.ad/Scripts/widget.aspx?" + qs;
        s.async = true;
        document.getElementById("contentad9733").appendChild(s);
    })();</script> </div>',
				'toobla' => "<div id=\"taboola-below-article-thumbnails\"></div>
<script type=\"text/javascript\">
  window._taboola = window._taboola || [];
  _taboola.push({
    mode: 'thumbnails-c',
    container: 'taboola-below-article-thumbnails',
    placement: 'Below Article Thumbnails',
    target_type: 'mix'
  });
</script>"

);
	//$rand = array_rand($providers);
//	$rand = get_input('show_ad', 'toobla');
	echo '<div class="content-block-ratator">' .$providers['toobla'] .  '</div>';
//	echo '<div class="content-block-ratator">' .$providers['contentad']. '</div>';
	break;	
	case 'content.ad':
	        echo '<div class="contentad"><div id="contentad9733"></div>
<script type="text/javascript">
    (function() {
        var params =
        {
            id: "67c8c761-6755-4867-92be-6317f1ea173a",
            d:  "bWluZHMuY29t",
            wid: "9733",
            cb: (new Date()).getTime()
        };

        var qs="";
        for(var key in params){qs+=key+"="+params[key]+"&"}
        qs=qs.substring(0,qs.length-1);
        var s = document.createElement("script");
        s.type= "text/javascript";
        s.src = "//api.content.ad/Scripts/widget.aspx?" + qs;
        s.async = true;
        document.getElementById("contentad9733").appendChild(s);
    })();</script> </div>';
	break;
	case 'content.ad-side':
		echo '<div class="contentad-side"><div id="contentad11261"></div>
<script type="text/javascript">
    (function() {
        var params =
        {
            id: "6577b456-0103-46fc-b54d-aeea66a87fec",
            d:  "bWluZHMuY29t",
            wid: "11261",
            cb: (new Date()).getTime()
        };

        var qs="";
        for(var key in params){qs+=key+"="+params[key]+"&"}
        qs=qs.substring(0,qs.length-1);
        var s = document.createElement("script");
        s.type= "text/javascript";
        s.src = "//api.content.ad/Scripts/widget.aspx?" + qs;
        s.async = true;
        document.getElementById("contentad11261").appendChild(s);
    })();
</script></div>';
	break;
	case 'toobla-side':
		echo "<div class='toobla-side'><div id='taboola-right-rail'></div>
<script type='text/javascript'>

window._taboola = window._taboola || [];

_taboola.push({mode:'thumbs-4r', container:'taboola-right-rail',
placement:'right-rail'});

</script></div>";
	break;
	case "content-header":
		echo "<div id=\"taboola-header-thumbnails\"></div>
<script type=\"text/javascript\">
  window._taboola = window._taboola || [];
  _taboola.push({
    mode: 'thumbnails-a',
    container: 'taboola-header-thumbnails',
    placement: 'Header Thumbnails',
    target_type: 'mix'
  });
</script>";
	break;
	default:
		echo '';
}
