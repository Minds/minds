<?php

/**
 * Action that renders an edit form using an instance of hjForm
 * If subject_entity is supplied, sets the input values to those of the entity and populates hidden fields with extras
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category AJAX
 * @category User Interface
 *
 * @return json
 */
$params = hj_framework_extract_params_from_url();

// We want to see the form
$form_guid = elgg_extract('form_guid', $params);
$form = get_entity($form_guid);

$html = elgg_view_entity($form, $params);

if (empty($html)) {
    $html = elgg_echo('hj:framework:ajax:noentity');
}

$output['data'] = $html;
print(json_encode($output));
return true;
