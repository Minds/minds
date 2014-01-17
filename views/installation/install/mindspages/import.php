<?php
    $url_bits = parse_url(elgg_get_site_url());

?>

<div id="minds-import" >
    <?php
        $vars['type'] = 'import';

        $url = current_page_url();

        $form_vars = array(
                'action' => $url,
                //'disable_security' => TRUE,
	);

        echo elgg_view_form('install/mindstemplate', $form_vars, $vars);
    ?>
</div>
