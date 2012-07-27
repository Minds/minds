<?php
global $CONFIG;

$extract = hj_framework_extract_params_from_url();
$params = elgg_extract('params', $extract, array());

$subject = $params['subject'];
$container = $params['container'];
$owner = $params['owner'];
$form = $params['form'];
$fields = $params['fields'];

$files = hj_framework_get_entities_by_priority('object', 'hjfile', $owner->guid, $container->guid);

if ($files) {
    foreach ($files as $file) {
        $options = array(elgg_view_entity($file) => $file->guid);
    }
    echo '<div><label>' . elgg_echo('hj:framework:email:attachments') . '</label></div>';

    echo '<div>';
    echo elgg_view('input/checkboxes', array(
        'selected' => true,
        'options' => $options,
        'internalname' => 'attachments',
    ));
    echo '</div>';
}