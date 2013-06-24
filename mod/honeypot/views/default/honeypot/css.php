<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$color = elgg_get_plugin_setting('color', 'honeypot');


if(empty($color)){
    $color = "#FFFFFF";
    }

?>

    .email_cover {
        display: block;
        position: absolute;
        width: 500px;
        height: 40px; 
        background-color: <?php echo $color; ?>;
    }

