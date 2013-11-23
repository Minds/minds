<?php
$filter = (array) elgg_extract("filter", $vars);
if (!empty($filter)) {
	foreach($filter as $tag) {
		echo elgg_view("input/hidden", array("name" => "filter[]", "value" => $tag));
	}
}

echo elgg_view("input/text", array("value" => get_input("faq_query"), "name" => "faq_query", "placeholder" => elgg_echo("search")));