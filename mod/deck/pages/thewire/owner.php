<?php
/**
 * User's wire posts
 * 
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward(REFERER);
}

elgg_push_breadcrumb($owner->name, 'profile/' . $owner->name);
elgg_push_breadcrumb(elgg_echo('thewire:breadcrumb:user'));

$title = elgg_echo('thewire:user', array($owner->name));

$loader = elgg_view('graphics/ajax_loader', array('hidden' => false));

$content = <<<HTML
<div id="{$owner->guid}-river-activity" class="column-river">
	<ul id="json-river-owner" class="column-header hidden" data-network="elgg" data-river_type="entity_river" data-entity="{$owner->guid}" data-subtypes_filter="{&quot;0&quot;: &quot;thewire&quot;}"></ul>
	<ul class="elgg-river elgg-list">$loader</ul>
</div>
HTML;

$body = elgg_view_layout('content', array(
	'filter_override' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('thewire/sidebar'),
));

echo elgg_view_page($title, $body);
