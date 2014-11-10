<?php

$form_params = array(
	'class' => 'elgg-form-launch',
);
echo elgg_view_form('nodes_upgrade', $form_params, array('node'=>$vars['node']));
