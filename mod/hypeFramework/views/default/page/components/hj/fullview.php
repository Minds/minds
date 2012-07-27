<?php

$entity_guid = elgg_extract('entity_guid', $vars);
$entity = get_entity($entity_guid);
$content = elgg_extract('content', $vars);
$handler = elgg_extract('handler', $vars);

$params = elgg_clean_vars($vars);
$params = hj_framework_extract_params_from_params($params);



$wrapped_content = <<<HTML
    <div class="hj-entity-footer hj-footer-menu">
        $footer_menu
    </div>
    <div id="hj-fullview-$entity->guid" class="hj-fullview">
        <div id="hj-gallery-$entity->guid" class="hj-gallery-view">
            $content
        </div>
    </div>
HTML;

$wrapped_content .= elgg_view('page/components/hj/fullview/extend');
echo $wrapped_content;
