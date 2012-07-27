<?php

function hj_framework_setup() {
    if (elgg_is_logged_in()) {
        hj_framework_setup_filefolder_form();
        hj_framework_setup_file_form();
        elgg_set_plugin_setting('hj:framework:setup', true);
        return true;
    }
    return false;
}

function hj_framework_setup_filefolder_form() {
//Setup Filefolder Form
    $form = new hjForm();
    $form->title = 'hypeFramework:filefolder';
    $form->label = 'File Folder Creation Form';
    $form->description = 'hypeFramework File Folder Creation Form';
    $form->subject_entity_subtype = 'hjfilefolder';
    $form->notify_admins = false;
    $form->add_to_river = false;
    $form->comments_on = false;
    $form->ajaxify = true;

    if ($form->save()) {
        $form->addField(array(
            'title' => 'Name of the folder',
            'name' => 'title',
            'mandatory' => true
        ));
        $form->addField(array(
            'title' => 'Description',
            'name' => 'description',
            'input_type' => 'longtext',
            'class' => 'elgg-input-longtext'
        ));
        $form->addField(array(
            'title' => 'Tags',
            'name' => 'tags',
            'input_type' => 'tags'
        ));
        $form->addField(array(
            'title' => 'Folder Type',
            'name' => 'datatype',
            'input_type' => 'dropdown',
            'options_values' => "hj_framework_get_filefolder_types();"
        ));
        $form->addField(array(
            'title' => 'Access Level',
            'name' => 'access_id',
            'input_type' => 'access'
        ));
        return true;
    }
    return false;
}

function hj_framework_setup_file_form() {
//Setup Files Form
    $form = new hjForm();
    $form->title = 'hypeFramework:fileupload';
    $form->label = 'File Upload Form';
    $form->description = 'hypeFramework File Upload Form';
    $form->subject_entity_subtype = 'hjfile';
    $form->notify_admins = false;
    $form->add_to_river = true;
    $form->comments_on = true;

    if ($form->save()) {
        $form->addField(array(
            'title' => 'Name of the File',
            'name' => 'title',
            'mandatory' => true
        ));
        $form->addField(array(
            'title' => 'Description',
            'name' => 'description',
            'input_type' => 'longtext',
            'class' => 'elgg-input-longtext'
        ));
        $form->addField(array(
            'title' => 'Tags',
            'name' => 'tags',
            'input_type' => 'tags'
        ));
        $form->addField(array(
            'title' => 'Folder Name',
            'tooltip' => 'elgg_echo("if empty, create one below")',
            'name' => 'filefolder',
            'input_type' => 'dropdown',
            'options_values' => 'hj_framework_get_user_file_folders();'
        ));
        $form->addField(array(
            'title' => 'Create new folder',
            'name' => 'newfilefolder'
        ));
        $form->addField(array(
            'title' => 'Upload',
            'name' => 'fileupload',
            'input_type' => 'file',
            'mandatory' => true
        ));
        $form->addField(array(
            'title' => 'Access Level',
            'name' => 'access_id',
            'input_type' => 'access'
        ));
        return true;
    }
    return false;
}
