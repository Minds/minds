<div id="minds-footer" >
    <?php
        $vars['type'] = 'footer';

        $url = current_page_url();

        $form_vars = array(
                'action' => $url,
                //'disable_security' => TRUE,
        	//'enctype' => 'multipart/form-data'
	);

        echo elgg_view_form('install/mindstemplate', $form_vars, $vars);
    ?>
</div>
