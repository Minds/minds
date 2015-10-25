<?php
/**
 * Template handler for emails
 */

$entity = elgg_extract('entity', $vars);

echo elgg_view('notification/email/header', $vars);
echo elgg_view('notification/email/body', $vars);
echo elgg_view('notification/email/footer', $vars);
