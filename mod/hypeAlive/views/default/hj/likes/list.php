<?php
$string = elgg_extract('value', $vars, '');
$count = elgg_extract('count', $vars, 0);
$params = elgg_extract('params', $vars, array());
$params = htmlentities(json_encode($params), ENT_QUOTES, 'UTF-8');

if ($count == 0) {
    $hidden = "hidden";
}

$output = <<<HTML
    <div class="hj-comments-bubble hj-likes-summary $hidden" data-options="$params">$string</div>
HTML;

echo $output;