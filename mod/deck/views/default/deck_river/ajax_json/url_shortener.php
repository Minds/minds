<?php

$longUrl = get_input('url', 'false');

if (!$longUrl) {
	echo elgg_echo('deck_river:url-not-exist');
	return;
}

if (filter_var($longUrl, FILTER_VALIDATE_URL)) {
	$shortUrl = goo_gl_short_url($longUrl);
	if ($shortUrl) {
		echo $shortUrl;
	} else {
		echo $longUrl;
	}
} else {
	echo 'badurl';
}