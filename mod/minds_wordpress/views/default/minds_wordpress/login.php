<?php
$wp = new minds\plugin\minds_wordpress\minds_wordpress();
echo elgg_view('input/hidden', array('name'=>'wp_auth', 'value'=>get_input('wp_auth', true)));
echo elgg_view('input/hidden', array('name'=>'forward', 'value'=>get_input('forward', true)));