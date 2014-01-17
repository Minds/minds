<?php
    $url_bits = parse_url(elgg_get_site_url());

?>

<div id="minds-theme" >
    <?php
        $vars['type'] = 'theme';

        $url = current_page_url();

        $form_vars = array(
                'action' => $url,
                //'disable_security' => TRUE,
        	'enctype' => 'multipart/form-data'
	);

        echo elgg_view_form('install/mindstemplate', $form_vars, $vars);
    ?>
</div>
