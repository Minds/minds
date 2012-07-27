<?php

/**
 * Action to perform on hjForm submit
 * Saves an entity of a given type and subtype (@see hjForm::$subject_entity_type, hjForm::$subject_entity_subtype)
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category AJAX
 * @category User Interface
 * @category Forms
 */

// In case we want to prevent from saving the new entity
if (!elgg_trigger_event('beforeSubmit', 'object')) {
    return true;
}

// Hack to allow non-logged in users to submit their forms
$access = elgg_get_ignore_access();
if (!elgg_is_logged_in()) {
    elgg_set_ignore_access(true);
}

$params = json_decode(get_input('params'), true);
foreach ($params as $key => $param) {
    if ($param == 'false') {
	$params[$key] = false;
    } else if ($params == 'true') {
	$params[$key] = true;
    }
}

$subject = get_entity($params['subject_guid']);
$container = get_entity($params['container_guid']);
$owner = get_entity($params['owner_guid']);
$form = get_entity($params['form_guid']);
if (elgg_instanceof($form, 'object', 'hjform')) {
    $fields = $form->getFields();
}
$widget = get_entity($params['widget_guid']);
$segment = get_entity($params['segment_guid']);
$context = $params['context'];

if ($subject->guid) {
    $event = 'update';
} else {
    $event = 'create';
}

elgg_make_sticky_form($form->title);

if (is_array($fields)) {
    switch ($form->subject_entity_type) {
        case 'object' :
        default :
            $formSubmission = new ElggObject($subject->guid);
            break;
        case 'user' :
            $formSubmission = new ElggUser($subject->guid);
            break;
        case 'group' :
            $formSubmission = new ElggGroup($subject->guid);
            break;
    }

    $formSubmission->subtype = $form->subject_entity_subtype;
    $formSubmission->title = get_input('title');
    $formSubmission->description = get_input('description');
    $formSubmission->owner_guid = $owner->guid;
    $formSubmission->container_guid = $container->guid;
    $formSubmission->access_id = get_input('access_id', elgg_extract('access_id', $params, $container->access_id));
    $saved = $formSubmission->save();

	$formSubmission->data_pattern = $form->guid;
	$formSubmission->widget = $widget->guid;
    $formSubmission->segment = $segment->guid;
    $formSubmission->handler = $form->handler;
    $formSubmission->notify_admins = $form->notify_admins;
    $formSubmission->add_to_river = $form->add_to_river;
    $formSubmission->comments_on = $form->comments_on;

    $params['guid'] = $saved;
}

if ($saved && is_array($fields)) {
    hj_framework_set_entity_priority($formSubmission);
    foreach ($fields as $field) {
        if ((!elgg_is_logged_in() && $field->access_id == ACCESS_PUBLIC) || (elgg_is_logged_in())) {
            $field_name = $field->name;
            $field_value = get_input($field_name);

            switch ($field->input_type) {
                default :
                    $formSubmission->$field_name = $field_value;
                    elgg_trigger_plugin_hook('hj:framework:field:process', 'all', array('entity' => $formSubmission, 'field' => $field), true);

                    // Do we need to treat the field in a special way?
                    break;

                case 'tags' :
                    $tags = explode(",", $field_value);
                    $formSubmission->$field_name = $tags;

                case 'file' :
                    if (elgg_is_logged_in()) {
                        global $_FILES;
                        $file = $_FILES[$field_name];

                        // Maybe someone doesn't want us to save the file in this particular way
                        if (!empty($file['name']) && !trigger_plugin_hook('hj:framework:form:fileupload', 'all', array('entity' => $file), false)) {
                            $newfilefolder = get_input('newfilefolder');
                            $filefolder = get_input('filefolder');

                            if ($form->subject_entity_subtype != 'hjfile') {
                                $formSubmission->filefolder = null;
                                $formSubmission->newfilefolder = null;
                            }
                            if ((int) $filefolder > 0) {
                                $filefolder = get_entity(get_input('filefolder'));
                            } else if ($newfilefolder) {
                                $filefolder = new ElggObject();
                                $filefolder->title = $newfilefolder;
                                $filefolder->subtype = 'hjfilefolder';
                                $filefolder->datatype = 'default';
                                $filefolder->data_pattern = hj_framework_get_data_pattern('object', 'hjfilefolder');
                                $filefolder->owner_guid = $owner->guid;
                                $filefolder->container_guid = $formSubmission->getGUID();
                                $filefolder->access_id = $formSubmission->access_id;
                                $filefolder->save();

                                hj_framework_set_entity_priority($filefolder);
                            } else {
								$filefolder = $formSubmission;
							}

                            // Just in case we want to upload a newer version of the file in the future
                            if ($file_guid = get_input("{$field_name}_guid")) {
                                $existing_file = true;
                                $file_guid = (int) $file_guid;
                            } else {
                                $existing_file = false;
                                $file_guid = null;
                            }

                            if (!$file_title = get_input("{$field_name}_title")) {
                                $file_title = get_input('title');
                            }
                            if (!$file_description = get_input("{$field_name}_description")) {
                                $file_description = get_input('description');
                            }
                            if (!$file_tags = get_input("{$field_name}_tags")) {
                                $file_tags = get_input('tags');
                                $file_tags = explode(',', $file_tags);
                            }

                            $filehandler = new hjFile($file_guid);
                            $filehandler->owner_guid = elgg_get_logged_in_user_guid();
                            $filehandler->container_guid = $filefolder->getGUID();
                            $filehandler->access_id = $filefolder->access_id;
                            $filehandler->data_pattern = hj_framework_get_data_pattern('object', 'hjfile');
                            $filehandler->title = $file_title;
                            $filehandler->description = $file_description;
                            $filehandler->tags = $file_tags;

                            $prefix = "hjfile/";

                            if ($existing_file) {
                                $filename = $filehandler->getFilenameOnFilestore();
                                if (file_exists($filename)) {
                                    unlink($filename);
                                }
                                $filestorename = $filehandler->getFilename();
                                $filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
                            } else {
                                $filestorename = elgg_strtolower($file['name']);
                            }

                            $filehandler->setFilename($prefix . $filestorename);
                            $filehandler->setMimeType($file['type']);
                            $filehandler->originalfilename = $file['name'];
                            $filehandler->simpletype = file_get_simple_type($file['type']);
                            $filehandler->filesize = round($file['size'] / (1024 * 1024), 2) . "Mb";

                            $filehandler->open("write");
                            $filehandler->close();
                            move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

                            $file_guid = $filehandler->save();

                            hj_framework_set_entity_priority($filehandler);
                            elgg_trigger_plugin_hook('hj:framework:file:process', 'object', array('entity' => $filehandler));

                            if ($file_guid) {
                                $formSubmission->$field_name = $filehandler->getGUID();
                            } else {
                                $formSubmission->$field_name = $filehandler->getFilenameOnFilestore();
                            }

                            if ($file_guid && $filehandler->simpletype == "image") {

                                $thumb_sizes = array(
                                    'tiny' => 16,
                                    'small' => 25,
                                    'medium' => 40,
                                    'large' => 100,
                                    'preview' => 250,
                                    'master' => 600,
                                    'full' => 1024,
                                );

                                foreach ($thumb_sizes as $thumb_type => $thumb_size) {
                                    $square = false;
                                    if (in_array($thumb_type, array('tiny', 'small', 'medium', 'large'))) {
                                        $square = true;
                                    }
                                    $thumbnail = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $thumb_size, $thumb_size, $square, 0, 0, 0, 0, true);
                                    if ($thumbnail) {
                                        $thumb = new ElggFile();
                                        $thumb->setMimeType($file['type']);

                                        $thumb->setFilename("{$prefix}{$filehandler->getGUID()}{$thumb_type}.jpg");
                                        $thumb->open("write");
                                        $thumb->write($thumbnail);
                                        $thumb->close();

                                        $thumb_meta = "{$thumb_type}thumb";
                                        $filehandler->$thumb_meta = $thumb->getFilename();
                                        unset($thumbnail);
                                    }
                                }
                            }
                        }
                    }
                    break;
            }
        }
    }
}
if ($saved) {
    // In case we want to manipulate received data
    if (elgg_trigger_plugin_hook('hj:framework:form:process', 'all', $params, true)) {

        if ($formSubmission->notify_admins) {
            $admins = elgg_get_admins();
            foreach ($admins as $admin) {
                $to[] = $admin->guid;
            }
            $from = elgg_get_config('site')->guid;
            $subject = sprintf(elgg_echo('hj:formbuilder:formsubmission:subject'), $form->title);
            elgg_push_context('admin');
            $submissions_url = elgg_normalize_url('hjform/submissions/' . $form->guid);
            $message = sprintf(elgg_echo('hj:formbuilder:formsubmission:body'), elgg_view_entity($formSubmission, $params), $submissions_url);
            notify_user($to, $from, $subject, $message);
            elgg_pop_context();
        }
        if ($formSubmission->add_to_river) {
            $view = "river/$formSubmission->type/$formSubmission->subtype/$event";
            if (!elgg_view_exists($view)) {
                $view = "river/object/hjformsubmission/create";
            }
            add_to_river($view, "$event", elgg_get_logged_in_user_guid(), $formSubmission->guid);
        }
        system_message(elgg_echo('hj:formbuilder:submit:success'));

        $fake_xhr = get_input('xhr', false);
        if (elgg_is_xhr() || $fake_xhr) {
            $newFormSubmission = get_entity($formSubmission->guid);
            if ($widget && elgg_instanceof($widget, 'object', 'widget')) {
                elgg_push_context('widgets');
            }
            $output['data'] = "<li id=\"elgg-{$newFormSubmission->getType()}-$newFormSubmission->guid\" class=\"elgg-item hj-view-entity elgg-state-draggable\">" . elgg_view_entity($newFormSubmission, $params) . "</li>";
            if (elgg_in_context('widgets')) {
                elgg_pop_context();
            }
            elgg_set_ignore_access($access);
            if ($fake_xhr) {
                if (elgg_get_context() == 'fancybox') {
		    elgg_pop_context();
		}
		header('Content-Type: application/json');
                $output['output']['data'] = $output['data'];
                unset($output['data']);
                print(json_encode($output));
                exit;
            } else {
                print(json_encode($output));
                return true;
            }
        }
        if ($form->subject_entity_subtype == 'hjsegment') {
            $url = "{$container->getURL()}&sg={$formSubmission->guid}";
        } else {
            $url = $formSubmission->getURL();
        }
		if (!elgg_is_logged_in() && $formSubmission->access_id !== ACCESS_PUBLIC) {
			$url = '';
		}
        elgg_clear_sticky_form($form->title);
        forward($url);
    }
}

elgg_set_ignore_access($access);
register_error(elgg_echo('hj:formbuilder:submit:error'));
forward(REFERER);
