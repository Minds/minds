<?php
/**
 * Minds Tools Setting - for adsense ID ATM
 */
 ?>
<p>Want a share of the revenue? Paste your ad block below (we recomend 200px x 200px). We'll give you one block on each of your blogs.</p>
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
	echo elgg_view('input/plaintext', array('name'=>'params[adblock]', 'value'=>$user_default ? $user_default : $default)); 

?>
