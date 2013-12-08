<?php
/**
 * View conversation thread
 */

$wire_guid = get_input('guid');
$wire = get_entity($wire_guid);

if (!$wire) {
	register_error(elgg_echo('noaccess'));
	forward(REFERER);
}

$owner = $wire->getOwnerEntity();
if (!$owner) {
	forward(REFERER);
}

$title = elgg_echo('thewire:by', array($owner->name));

elgg_push_breadcrumb(elgg_echo('thewire:user', array($owner->name)), 'message/owner/' . $owner->name);
elgg_push_breadcrumb($title);

$loader = elgg_view('graphics/ajax_loader', array('hidden' => false));

//$content = '<ul class="elgg-river elgg-list single-view">' . elgg_view('graphics/ajax_loader', array('hidden' => false)) . '</ul>';

$content = <<<HTML
<div id="message-river-activity" class="column-river">
	<ul class="column-header hidden" data-network="elgg" data-river_type="entity_river" data-entity="{$river_id}"></ul>
	<ul class="elgg-river elgg-list single-view">$loader</ul>
</div>
HTML;

elgg_load_library('deck_river:river_loader');
$thread = htmlspecialchars(load_wire_discussion($wire->wire_thread, true), ENT_QUOTES, "UTF-8");
$river = elgg_get_river(array('object_guid' => $wire_guid));
$river_id = $river[0]->id;
$content .= "<div id=\"json-river-thread\" class=\"hidden\" data-message-id=\"$river_id\">$thread</div>";

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
