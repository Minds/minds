<?php
/**
 * Contribution wizard page
 */

$content = elgg_view('kaltura/upload');

$body = elgg_view_layout("one_column", array(
					'content' => $content, 
					'sidebar' => false,
					'filter_override' => elgg_view('page/layouts/content/archive_filter', $vars),
					'title' => elgg_echo('upload')
					));
					
// Display page
echo elgg_view_page(elgg_echo('upload'),$body);
