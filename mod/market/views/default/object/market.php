<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

$full = elgg_extract('full_view', $vars, FALSE);
$marketpost = $vars['entity'];

elgg_load_library('elgg:pay');

if (!$marketpost) {
	return TRUE;
}

$currency = elgg_get_plugin_setting('market_currency', 'market');

$owner = $marketpost->getOwnerEntity();
$tu = $marketpost->time_updated;
$container = $marketpost->getContainerEntity();
$category = "<b>" . elgg_echo('market:category') . ":</b> " . elgg_echo("market:{$marketpost->marketcategory}");
$excerpt = elgg_get_excerpt($marketpost->description);

$owner_link = elgg_view('output/url', array(
	'href' => "market/owned/{$owner->username}",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$image = elgg_view('market/thumbnail', array('marketguid' => $marketpost->guid, 'size' => 'medium', 'tu' => $tu));
$market_img = elgg_view('output/url', array(
	'href' => "market/view/$owner->username",
	'text' => $image,
));

$tags = elgg_view('output/tags', array('tags' => $marketpost->tags));
$date = elgg_view_friendly_time($marketpost->time_created);

if(isset($marketpost->custom) && elgg_get_plugin_setting('market_custom', 'market') == 'yes'){
	$custom = "<br><b>" . elgg_echo('market:custom:text') . ": </b>" . elgg_echo($marketpost->custom);
}

$comments_count = $marketpost->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $marketpost->getURL() . '#market-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'market',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {

	$body = "<table border='0' width='100%'><tr>";
	$body .= "<td width='220px'><center>";
	$body .= elgg_view('output/url', array(
			'href' => elgg_get_site_url() . "mod/market/viewimage.php?marketguid={$marketpost->guid}",
			'text' => elgg_view('market/thumbnail', array('marketguid' => $marketpost->guid, 'size' => 'large', 'tu' => $tu)),
			'class' => "elgg-lightbox",
			));
	$body .= "</center></td><td>";
	if ($allowhtml != 'yes') {
		$body .= autop(parse_urls(strip_tags($marketpost->description)));
	} else {
		$body .= elgg_view('output/longtext', array('value' => $marketpost->description));
	}
	$body .= "</td></tr><tr>";
	$body .= "<td><center>";
	//$body .= "<span class='market_pricetag'><b>" . elgg_echo('market:price') . "</b> {$currency}{$marketpost->price}</span>";
	$body .= pay_basket_add_button($marketpost->guid, $marketpost->title, $marketpost->description, $marketpost->price, 1);
	$body .= "</center></td><td><center>";
	if (elgg_get_plugin_setting('market_pmbutton', 'market') == 'yes') {
		if ($owner->guid != elgg_get_logged_in_user_guid()) {
			$body .= elgg_view('output/url', array(
							'class' => 'elgg-button elgg-button-action',
							'href' => "messages/compose?send_to={$owner->guid}",
							'text' => elgg_echo('market:pmbuttontext'),
							));
		}
	}
	$body .= "</center></td></tr></table>";

	$subtitle = "{$category}{$custom}<br>{$author_text} {$date} {$comments_link}";


	$params = array(
		'entity' => $marketpost,
		'header' => $header,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
	$owner_icon = elgg_view_entity_icon($owner, 'small');
	$marketpost_info = elgg_view_image_block($owner_icon, $list_body);

	echo <<<HTML
$marketpost_info
<div class="market elgg-content">
	$body
</div>
HTML;

} elseif (elgg_in_context('gallery')) {
	echo '<div class="market-gallery-item">';
	echo "<h3>{$category}: {$marketpost->title}</h3>";
	echo elgg_view_entity_icon($marketpost, 'medium');
	echo "<p class='subtitle'>$owner_link $date</p>";
	echo '</div>';
} else {
	// brief view
	$market_img = elgg_view('output/url', array(
			'href' => "market/view/{$marketpost->guid}/" . elgg_get_friendly_title($marketpost->title),
			'text' => elgg_view('market/thumbnail', array('marketguid' => $marketpost->guid, 'size' => 'medium', 'tu' => $tu)),
			));

	$subtitle = "{$category}<br><b>" . elgg_echo('market:price') . ":</b> {$currency}{$marketpost->price}{$custom}";
	$subtitle .= "<br>{$author_text} {$date} {$comments_link}";


	$params = array(
		'entity' => $marketpost,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($market_img, $list_body);
}

