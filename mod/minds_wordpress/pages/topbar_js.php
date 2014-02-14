<?php

// JS Embed a minds toolbar on a wordpress site. This is a bit of a hack, there are probably easier ways...

header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' ); 
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); 
header( 'Cache-Control: no-store, no-cache, must-revalidate' ); 
header( 'Cache-Control: post-check=0, pre-check=0', false ); 
header( 'Pragma: no-cache' ); 

?>
var div = document.getElementById("Minds-Toolbar");


function mindsGetToolbarCSS() {
    return "<?php
    
        $css = "<style type=\"text/css\">\n" . elgg_view('wordpress/css/css') . "</style>";
        $css = str_replace("\n", ' ', $css);
        $css = addslashes($css);
        
        echo $css;
    
    ?>";
}

function mindsGetToolbar() {
    return "<?php 
    
        $toolbar = elgg_view('wordpress/page/elements/toolbar');
        $toolbar = str_replace("\n", ' ', $toolbar);
        $toolbar = addslashes($toolbar);
        
        echo $toolbar;
    
    ?>";

}

div.innerHTML= mindsGetToolbarCSS() + mindsGetToolbar();