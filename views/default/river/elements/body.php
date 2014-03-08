<?php
/**
 * Body of river item
 *
 * @uses $vars['item']        ElggRiverItem
 * @uses $vars['summary']     Alternate summary (the short text summary of action)
 * @uses $vars['message']     Optional message (usually excerpt of text)
 * @uses $vars['attachments'] Optional attachments (displaying icons or other non-text data)
 * @uses $vars['responses']   Alternate respones (comments, replies, etc.)
 */

$item = $vars['item'];

$message = elgg_extract('message', $vars, false);
if ($message !== false) {
	$message = "<div class=\"minds-river-message\">$message</div>";
}

$attachments = elgg_extract('attachments', $vars, false);
if ($attachments !== false) {
	$attachments = "<div class=\"minds-river-attachments clearfix\">$attachments</div>";
}

$responses = elgg_view('river/elements/responses', $vars);
if ($responses) {
	$responses = "<div class=\"minds-river-responses\">$responses</div>";
}


echo <<<RIVER
$message
$attachments
$responses
RIVER;
