<?php
/**
 * Main content area layout
 *
 * @uses $vars['content']        HTML of main content area
 * @uses $vars['sidebar']        HTML of the sidebar
 * @uses $vars['header']         HTML of the content area header (override)
 * @uses $vars['nav']            HTML of the content area nav (override)
 * @uses $vars['footer']         HTML of the content area footer
 * @uses $vars['filter']         HTML of the content area filter (override)
 * @uses $vars['title']          Title text (override)
 * @uses $vars['context']        Page context (override)
 * @uses $vars['buttons']        Content header buttons (override)
 * @uses $vars['filter_context'] Filter context: everyone, friends, mine
 * @uses $vars['class']          Additional class to apply to layout
 */

// navigation defaults to breadcrumbs
$nav = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

// allow page handlers to override the default filter
if (isset($vars['filter'])) {
	$vars['filter_override'] = $vars['filter'];
}
$filter = elgg_view('page/layouts/content/filter', $vars);

// the all important content
$content = elgg_extract('content', $vars, '');

// optional footer for main content area
$footer_content = elgg_extract('footer', $vars, '');
$params = $vars;
$params['content'] = $footer_content;
$footer = elgg_view('page/layouts/content/footer', $params);

$params = array(
	'title' => $vars['title'],
	'content' => $filter . $content . $footer,
	'sidebar' => elgg_extract('sidebar_alt', $vars, ''),
	'sidebar_alt' => elgg_extract('sidebar', $vars, ''),
);
if (isset($vars['class'])) {
	$params['class'] = $vars['class'];
}
echo elgg_view_layout('two_sidebar', $params);