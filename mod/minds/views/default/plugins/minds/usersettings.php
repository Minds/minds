<?php
/**
 * Minds Tools Setting - for adsense ID ATM
 */
 ?>
<p>Want a share of the revenue? Paste your ad blocks below</p>
<?php 
	$user_default = elgg_get_plugin_user_setting('adblock', elgg_get_page_owner_guid(), 'minds');
	$default = '<script type="text/javascript"><!--
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
	echo elgg_view('output/text', array('value'=>'(336px wide)'));
	echo elgg_view('input/plaintext', array('name'=>'params[adblock]', 'value'=>$user_default ? $user_default : $default)); 

	$user_default = elgg_get_plugin_user_setting('adblock2', elgg_get_page_owner_guid(), 'minds');
        $default = '<script type="text/javascript"><!--
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
	echo elgg_view('output/text', array('value'=>'(336px wide)'));
        echo elgg_view('input/plaintext', array('name'=>'params[adblock2]', 'value'=>$user_default ? $user_default : $default));

	$user_default = elgg_get_plugin_user_setting('adblock3', elgg_get_page_owner_guid(), 'minds');
        $default = '<script type="text/javascript"><!--
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
	echo elgg_view('output/text', array('value'=>'(728px wide)'));
        echo elgg_view('input/plaintext', array('name'=>'params[adblock3]', 'value'=>$user_default ? $user_default : $default));


?>
