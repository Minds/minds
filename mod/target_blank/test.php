<?php 

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

admin_gatekeeper();

$body .= "<a href='http://www.google.nl'>Google.nl</a><br/>";
$body .= "<a href='http://www.google.nl' style='display: none;'>Google.nl (display: none)</a><br/>";
$body .= "<a href='https://www.google.nl'>Google.nl (HTTPS)</a><br/>";
$body .= "<a href='http://www.google.nl' target='_self'>Google.nl (target=_self)</a><br/>";
$body .= "<a href='/pg/news'>/pg/news</a><br/>";
$body .= "<a href='#'>#</a><br/>";
$body .= "<a href='javascript:void(0);'>javascript:void(0);</a><br/>";

echo elgg_view_page('Test', $body);