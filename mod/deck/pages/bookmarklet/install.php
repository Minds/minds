<?php
/* install bookmarklet */

$site = elgg_get_site_url();


$content = '<div class="pvl">' . elgg_echo('bookmarks:bookmarklet:description') . '</div>';

$content .= '<div class="row-fluid">';

$bookmarklet_popup_version = '1';
$popup = "<a class=\"elgg-button bookmarklet-link\" href=\"javascript:(function(){var%20w=795;var%20h=245;var%20x=Number((window.screen.width-w)/2);var%20y=Number((window.screen.height-h)/2);wdb=window.open('" .
	$site . "bookmarklet/popup?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)+'&b_popup_v=" .
	$bookmarklet_popup_version ."','deckBookmarklet','width='+w+',height='+h+',left='+x+',top='+y+',directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no');})();\">" .
	elgg_echo('bookmarklet:popup') . '</a>';

$content .= '<div class="span6">' . elgg_view_module(
			'aside',
			elgg_echo('bookmarklet:popup:title'),
			elgg_echo('bookmarklet:popup:install') . '<div class="center">' . $popup .'</div>'
		) . '</div>';


/*$content .= '<div class="span6">' . elgg_view_module(
			'aside',
			elgg_echo('bookmarklet:popin'),
			"<a href=\"javascript:(function(){console.log('heho');})();\">rnst</a>"
		) . '</div>';*/


$content .= '</div>';

$title = elgg_echo('bookmarklet');

$body = elgg_view_layout('content', array(
	'filter' => false,
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);


