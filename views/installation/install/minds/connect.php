<?php
    $url_bits = parse_url(elgg_get_site_url());

?><p>Optionally connect your site to the Minds network.</p>

<div id="minds-connect-keys" >
    <?php 
        $vars['type'] = 'minds';
	
	$url = current_page_url();
	
	$form_vars = array(
		'action' => $url,
		'disable_security' => TRUE,
	);
	
	echo elgg_view_form('install/mindstemplate', $form_vars, $vars); 
    ?>
</div>
