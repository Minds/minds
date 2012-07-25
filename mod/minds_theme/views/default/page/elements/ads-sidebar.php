<?php
/* 
 * Satheesh PM, BARC Mumbai
 * www.satheesh.anushaktinagar.net
 */

$adssidebar  = elgg_get_plugin_setting('ads_sidebar',  'Ads');
$view = elgg_get_plugin_setting('view_ads_sidebar', 'Ads');

$body = '<div id="ads-sidebar">'.$adssidebar.'</div>';
$body .= <<<HTML

     <script type="text/javascript">
    	$(document).ready(function(){ $('#ads-sidebar').jshowoff({

        changeSpeed:800,    //Speed of transition in milliseconds.
        speed:60000,         //Time each slide is shown in milliseconds.
        animatePause:true,  //Whether to use 'Pause' animation text when pausing.
        autoPlay:true,      //Whether to start playing immediately.
        controls:false,      //Whether to create & display controls (Play/Pause, Previous, Next).
        links:false,         //Whether to create & display numeric links to each slide.
        hoverPause:true,     //Whether to pause on hover.
        effect:'fade',      //Type of transition effect: 'fade', 'slideLeft' or 'none'.
        controlText:{ play:'Play', pause:'Pause', previous:'Previous', next:'Next' } 	//Text to use for controls (Play/Pause, Previous, Next).

        }); });
    </script>
HTML;

echo elgg_view_module($view, null, $body);

?>
