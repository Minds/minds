<?php

$form = $vars['entity'];

if (!elgg_instanceof($form)) {
    return true;
}

$view = "hj/forms/$form->subject_entity_type/$form->subject_entity_subtype";
if (elgg_view_exists($view)) {
    elgg_view($view, $vars);
    return true;
}

elgg_load_library('hj:framework:forms');
elgg_load_library('hj:framework:knowledge');
elgg_load_js('hj.framework.fieldcheck');
elgg_load_js('hj.formbuilder.sortable');


$fields = $form->getFields();

$form_title = elgg_echo($form->getTitle($subject));
$form_description = elgg_echo($form->description);

if (elgg_is_sticky_form($form->title)) {
    extract(elgg_get_sticky_values($form->title));
    elgg_clear_sticky_form($form->title);
}

if (is_array($fields)) {
    foreach ($fields as $field) {
        if ($field->input_type == 'file' || $field->input_type == 'entity_icon') {
            $multipart = true;
        }
        if (!$multipart || elgg_is_logged_in()) {
            $form_fields .= elgg_view_entity($field, $vars);
        }
    }
}

$params = elgg_clean_vars($vars);
unset($params['entity']);

//$params = hj_framework_json_query($params);

if (isset($params['subject_guid'])) {
    $params['event'] = 'update';
}

$form_fields .= elgg_view('input/hidden', array('value' => json_encode($params), 'name' => 'params'));

if (!isset($vars['ajaxify']) || $vars['ajaxify'] === true) {
    $ajaxify = true;
}
if ($multipart && $ajaxify) {
    // a hack to return json values on form submit when sending files via get/post in ajaxsubmit
    $form_fields .= elgg_view('input/hidden', array('value' => true, 'name' => 'xhr'));
}

$form_fields .= elgg_view('input/submit', array(
    'value' => elgg_echo('submit')
        ));

if ($ajaxify) {
    $class = "hj-ajaxed-save";
    if ($multipart) {
        $class = "$class hj-ajaxed-file-save";
    }
}

$form = elgg_view('input/form', array(
    'body' => $form_fields,
    'id' => "hj-form-entity-{$form->guid}",
    'action' => $form->action,
    'method' => $form->method,
    'enctype' => $form->enctype,
    'class' => "$form->class $class",
    'js' => 'onsubmit="return hj.framework.fieldcheck.init($(this));"'
        ));

$body = elgg_view_module('aside', $form_title, $form_description . $form);
echo $body;