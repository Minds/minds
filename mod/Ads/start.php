<?php
/*
 * Satheesh PM, BARC Mumbai
 * www.satheesh.anushaktinagar.net
 * 
 */


elgg_register_event_handler('init', 'system', 'Ads_init');

function Ads_init() {
        $header_ads=elgg_get_plugin_setting('showads_header','Ads');
        $sidebar_ads=elgg_get_plugin_setting('showads_sidebar','Ads');
        $footer_ads=elgg_get_plugin_setting('showads_footer','Ads');

        elgg_extend_view('css/elgg', 'Ads/css');
        elgg_register_js('jquery.jshowoff.min', 'mod/Ads/js/jquery.jshowoff.min.js', 'head');
	elgg_load_js('jquery.jshowoff.min');
 	    if ($header_ads == yes) {
		if ((elgg_get_context() != 'activity') or (!elgg_is_active_plugin('river_activity_3C'))){
                	elgg_extend_view('page/layouts/content/header', 'page/elements/ads-header','1');
            	}
            }
            if ($sidebar_ads == yes) {
            elgg_extend_view('page/elements/sidebar', 'page/elements/ads-sidebar','700');
            }
            if ($footer_ads == yes){
            elgg_extend_view('page/elements/footer', 'page/elements/ads-footer', '701');
            }
}
?>
