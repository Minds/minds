<?php

$content = elgg_extract('content', $vars, false);
$grid = elgg_extract('grid', $vars, array());

if (!$content) {
    return true;
}

$num_columns = sizeof($content);

if (!$grid) {
    $equal = 12 / $num_columns;
    foreach ($content as $column_content) {
        $page .= "<div class=\"hj-grid-$equal\">$column_content</div>";
    }
} else {
    foreach ($content as $column => $column_content) {
        $page .= "<div class=\"hj-grid-$grid[$column]\">$column_content</div>";
    }
}
    
$page = <<<HTML
    <div class="hj-container-12 clearfix">
        $page
    </div>
HTML;

echo $page;
