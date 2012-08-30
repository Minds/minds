<?php
//Video width
echo '<p>';
echo elgg_echo('embed_extender:width');
echo elgg_view('input/text', array('name' => "params[width]", 'value' => $vars['entity']->width, 'class' => ' '));
echo '</p>';
echo '<p>';
echo elgg_echo('embed_extender:widget_width');
echo elgg_view('input/text', array('name' => "params[widget_width]", 'value' => $vars['entity']->widget_width, 'class' => ' '));
echo '</p>';

//Embed options
$wire_show = $vars['entity']->wire_show;
if (!$wire_show) $wire_show = 'no';

$blog_show = $vars['entity']->blog_show;
if (!$blog_show) $blog_show = 'no';

$comment_show = $vars['entity']->comment_show;
if (!$comment_show) $comment_show = 'no';

$topicposts_show = $vars['entity']->topicposts_show;
if (!$topicposts_show) $topicposts_show = 'no';

$messageboard_show = $vars['entity']->messageboard_show;
if (!$messageboard_show) $messageboard_show = 'no';

$page_show = $vars['entity']->page_show;
if (!$page_show) $page_show = 'no';

$bookmark_show = $vars['entity']->bookmark_show;
if (!$bookmark_show) $bookmark_show = 'no';

$custom_provider = $vars['entity']->custom_provider;
if (!$custom_provider) $custom_provider = 'no';

//Show in the wire?
echo '<p>';
echo elgg_echo('embed_extender:wire:show'); 
echo elgg_view('input/dropdown', array(
		'name' => 'params[wire_show]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no')
		),
		'value' => $wire_show
));
echo '</p>';

//Show in blog posts?
echo '<p>';
echo elgg_echo('embed_extender:blog:show');
echo elgg_view('input/dropdown', array(
		'name' => 'params[blog_show]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no')
		),
		'value' => $blog_show
));
echo '</p>';

//Show in comments?
echo '<p>';
echo elgg_echo('embed_extender:comment:show');
echo elgg_view('input/dropdown', array(
		'name' => 'params[comment_show]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no')
		),
		'value' => $comment_show
));
echo '</p>';

//Show in group posts?
echo '<p>';
echo elgg_echo('embed_extender:topicpost:show');
echo elgg_view('input/dropdown', array(
		'name' => 'params[topicposts_show]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no')
		),
		'value' => $topicposts_show
));
echo '</p>';

//Show in messageboard?
echo '<p>';
echo elgg_echo('embed_extender:messageboard:show');
echo elgg_view('input/dropdown', array(
		'name' => 'params[messageboard_show]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no')
		),
		'value' => $messageboard_show
));
echo '</p>';

//Show in pages?
echo '<p>';
echo elgg_echo('embed_extender:page:show');
echo elgg_view('input/dropdown', array(
		'name' => 'params[page_show]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no')
		),
		'value' => $page_show
));
echo '</p>';

//Show in bookmarks?
echo '<p>';
echo elgg_echo('embed_extender:bookmark:show');
echo elgg_view('input/dropdown', array(
		'name' => 'params[bookmark_show]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no')
		),
		'value' => $bookmark_show
));
echo '</p>';


//Show in custom views (for power users)
echo '<p>';
echo elgg_echo('embed_extender:custom:views');
echo elgg_view('input/plaintext', array('name' => 'params[custom_views]', 'value' => $vars['entity']->custom_views));
echo '</p>';


//Allow custom providers?
echo '<p>';
echo elgg_echo('embed_extender:custom_provider');
echo elgg_view('input/dropdown', array(
		'name' => 'params[custom_provider]',
		'options_values' => array(
			'yes' => elgg_echo('option:yes'),
			'no' => elgg_echo('option:no')
		),
		'value' => $custom_provider
));
echo '</p>';