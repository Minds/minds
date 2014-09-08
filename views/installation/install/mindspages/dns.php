<p> Please enter the following DNS settings to configure your domain.</p>

<p><b>CName:</b> multisite2loadbalancer2-1442974952.us-east-1.elb.amazonaws.com</p>

<?php


$vars['type'] = 'dns';

$url = current_page_url();

$form_vars = array(
        'action' => $url,
        'disable_security' => TRUE,
);

echo elgg_view_form('install/mindstemplate', $form_vars, $vars);
