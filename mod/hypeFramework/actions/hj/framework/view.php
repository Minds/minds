<?php

/**
 * Action that renders a list of entities
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category AJAX
 * @category User Interface
 *
 * @uses $view mixed guid of an entity or a view to render
 * @return json
 */
$view = get_input('e');
$params = get_input('params');

// Let's see if we need to render a view in a specific context
$context = elgg_extract('push_context', $params, 'framework');
unset($params['push_context']);
elgg_push_context($context);

if (elgg_view_exists($view)) {
    $html = elgg_view($view, $params);
} else {
    $entity = get_entity($view);
    if (elgg_instanceof($entity)) {
        //$entity_params = hj_framework_extract_params_from_entity($entity, $params);
        $html = elgg_view_entity($entity, $params);
    }
}

if (!$html) {
    register_error(elgg_echo('hj:framework:ajax:empty'));
} else {
    $output['data'] = $html;
    print(json_encode($output));
}

elgg_pop_context();

return true;
