<?php
elgg_load_css('hj.framework.base');
if (elgg_in_context('admin') || elgg_is_admin_logged_in()) {
    elgg_load_library('hj:framework:forms');

    $formSubmission = $vars['entity'];
    $form = get_entity($formSubmission->data_pattern);
    if ($form) {
        $fields = $form->getFields();
        
        $header = sprintf(elgg_echo('hj:formbuilder:submissionfor'), $form->getTitle());
        $body = sprintf(elgg_echo('hj:formbuilder:submissionfield'), 'Form GUID', $form->guid);
        $body .= sprintf(elgg_echo('hj:formbuilder:submissionfield'), 'Submission GUID', $formSubmission->guid);
        $body .= elgg_view('page/components/hj/fieldtable', array('entity_guid' => $formSubmission->guid, 'form_guid' => $form->guid));

        $module = elgg_view_module('info', $header, $body);

        echo $module;
    }
} else {
    return true;
}