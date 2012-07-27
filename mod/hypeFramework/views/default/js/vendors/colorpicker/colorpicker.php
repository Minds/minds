<?php
echo elgg_view('js/vendors/colorpicker/colorpicker.js');
?>
    elgg.provide('hj.framework.colorpicker');
       
    hj.framework.colorpicker.init = function() {
        $('.hj-color-picker').miniColors();
    };
    
    elgg.register_hook_handler('init', 'system', hj.framework.colorpicker.init);
    elgg.register_hook_handler('success', 'hj:framework:ajax', hj.framework.colorpicker.init);
