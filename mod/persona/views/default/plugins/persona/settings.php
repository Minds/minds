<?php

    /**
     * Persona administration settings
     */

	$instructions = elgg_echo('persona:settings:instructions', array(elgg_get_site_url()));

	$persona_enabled_string = elgg_echo('persona:login:use');
	$persona_enabled_view = elgg_view('input/dropdown', array(
		'name' => 'params[enable_sign_on]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no'),
		),
		'value' => $vars['entity']->enable_sign_on ? $vars['entity']->enable_sign_on : 'no',
	));

	$settings = <<<__HTML
	<div class="elgg-content-thin mtm"><p>$instructions</p></div>
	<div><label>$persona_enabled_string</label><br />$persona_enabled_view</div>
__HTML;

	echo $settings;
