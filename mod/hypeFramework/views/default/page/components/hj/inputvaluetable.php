<?php
$fields = elgg_extract('fields', $vars, null);

if (is_array($fields)) {
    foreach ($fields as $field) {
        $field_name = $field->name;
        if (get_input($field_name) != '' && !in_array($field->input_type, array('access', 'hidden', 'file', 'attachments'))) {
            $output_value = get_input($field_name);
            $output_type = $field->input_type;
            $output_label = elgg_echo($field->getLabel());
            $output_text = elgg_view("output/$output_type", array('value' => $output_value));
            //$output_icon = elgg_view_icon($entity->$field_name);
            if (!empty($output_value)) {
                $content .= <<<HTML
                        <div style="margin-bottom:5px;">
                            <span style="font-weight:bold;">$output_label: </span><br />
                            <span style="padding:10px;margin-left:10px;border:1px solid #f4f4f4">$output_text</span>
                        </div>
HTML;
            }
        }
    }
}

echo $content;
