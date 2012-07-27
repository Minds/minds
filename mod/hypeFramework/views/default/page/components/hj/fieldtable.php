<?php

$entity_guid = elgg_extract('entity_guid', $vars);
$entity = get_entity($entity_guid);

$intro = elgg_extract('intro', $vars, '');

$form_guid = elgg_extract('form_guid', $vars);
$form = get_entity($form_guid);

if (elgg_instanceof($form, 'object', 'hjform')) {
    $fields = $form->getFields();
}

if (is_array($fields)) {
    foreach ($fields as $field) {
        $field_name = $field->name;
        if ($entity->$field_name != '' && !in_array($field->input_type, array('access', 'hidden'))) {
            $output_type = $field->input_type;

            if ($output_type == 'dropdown') {
                $output_value = elgg_echo("{$field->name}:value:{$entity->$field_name}");
            } else {
                $output_value = $entity->$field_name;
            }
            $output_label = elgg_echo($field->getLabel());
            $output_text = elgg_view("output/$output_type", array('value' => $output_value, 'entity' => $entity));
            //$output_icon = elgg_view_icon($entity->$field_name);
            if (!empty($output_value)) {
                $content .= <<<HTML
                        <div class="hj-field-module-output clearfix">
                            $output_icon<span class="hj-field-module hj-output-label hj-left">$output_label: </span>
                            <span class="hj-field-module hj-output-text hj-left">$output_text</span>
                        </div>
HTML;
            }
        }
    }
}

echo $content;