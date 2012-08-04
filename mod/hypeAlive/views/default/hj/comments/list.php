<?php
$summary = elgg_extract('summary', $vars);
$visible = elgg_extract('visible', $vars, null);
$hidden = elgg_extract('hidden', $vars, null);

if ($summary) {
   $summary = "<div class=\"hj-comments-bubble hj-comments-summary\"><a href=\"javascript:void(0)\">$summary</a></div>";
}

$output = <<<HTML
	$summary
    $pointer
    <div class="hj-comments-list hj-comments-visible">$visible</div>
    <div class="hj-comments-hidden hidden">$hidden</div>
            

HTML;

echo $output;