<?php
/**
 * Group blog module
 */

$group = elgg_get_page_owner_entity();

if ($group->faq_enable != "yes") {
	return true;
}

$all_link = elgg_view("output/url", array(
	"href" => "user_support/faq/group/" . $group->getGUID() . "/all",
	"text" => elgg_echo("link:view:all"),
	"is_trusted" => true,
));

elgg_push_context("widgets");
$options = array(
	"type" => "object",
	"subtype" => UserSupportFAQ::SUBTYPE,
	"container_guid" => $group->getGUID(),
	"limit" => 6,
	"full_view" => false,
	"pagination" => false,
);
$content = elgg_list_entities_from_metadata($options);
elgg_pop_context();

if (!$content) {
	$content = elgg_view("output/longtext", array("value" => elgg_echo("user_support:faq:not_found")));
}

$new_link = "";
if ($group->canEdit()) {
	$new_link = elgg_view("output/url", array(
		"href" => "user_support/faq/add/" . $group->getGUID(),
		"text" => elgg_echo("user_support:menu:faq:create"),
		"is_trusted" => true,
	));
}

echo elgg_view("groups/profile/module", array(
	"title" => elgg_echo("user_support:menu:faq:group"),
	"content" => $content,
	"all_link" => $all_link,
	"add_link" => $new_link,
));
