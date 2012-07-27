<?php

/**
 * Obtain an array of input types
 * 
 * @return array 
 */
function hj_formbuilder_get_input_types_array() {
    $types = array(
        'text',
        'plaintext',
        'longtext',
        'url',
        'email',
        'date',
        'dropdown',
        'tags',
        'checkboxes',
        'file',
        'hidden',
        'radio',
        'access',
    );
    $types = elgg_trigger_plugin_hook('hj:formbuilder:fieldtypes', 'all', array('types' => $types), $types);

    return $types;
}

function hj_formbuilder_get_filefolder_types() {
    $types = array('default', 'audio', 'video', 'photo', 'design', 'docs', 'powerpoint');
    $types = elgg_trigger_plugin_hook('hj:formbuilder:foldertypes', 'all', array('types' => $types), $types);
    return $types;
}

/**
 * Create an options_values array for a dropdown of available hjForms
 *
 * @return array 
 */
function hj_formbuilder_get_forms_as_dropdown() {
    $forms = elgg_get_entities(array(
        'type' => 'object',
        'subtype' => 'hjform',
        'limit' => 0
            ));

    $options = array();
    //$options[] = 'select...';
    $options[] = elgg_echo('hj:formbuilder:formsdropdown:new');
    if (is_array($forms)) {
        foreach ($forms as $form) {
            //$form->delete();
            $options[$form->guid] = "$form->title";
        }
    }
    return $options;
}

function hj_formbuilder_get_forms_as_sections() {
    $forms = elgg_get_entities(array(
        'type' => 'object',
        'subtype' => 'hjform',
        'limit' => 0
            ));

    $core_subtypes = array('hjsegment', 'hjfile', 'hjfilefolder', 'hjcustommodule');

    $options = array();
    //$options[] = 'select...';
    if (is_array($forms)) {
        foreach ($forms as $form) {
            //$form->delete();
            if (!in_array($form->subject_entity_subtype, $core_subtypes)) {
                $options[$form->subject_entity_subtype] = "$form->subject_entity_subtype";
            }
        }
    }
    return $options;
}