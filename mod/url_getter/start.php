<?php

function url_getter_init() {
	if (!function_exists('curl_version')) {
		// see, this is why I want a dependency check callback in the core!!
		register_error('The URL Getter depends on CURL, but the library was not found. Please install it.');
	}

}

/**
 * $url: the URL to get
 * $args: array of other optional arguments as follows:
 *    username: username for HTTP auth
 *    password: password for HTTP auth
 *    post: boolean, is this a post? (defaults to False)
 *    data: HTTP body for POST
 *    headers: HTTP headers
 */
function url_getter_getUrl($url, $args = array()) {

	global $release;

	$userAgent = '(Elgg ' . $release . ')';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	if ($args['username'] && $args['password']) {
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, $args['username'] . ':' . $args['password']);
	}

	if ($args['post']) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $args['data']);
	}

	if ($args['headers']) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $args['headers']);
	}

	$html = curl_exec($ch);
	$rc = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	//print '[' . $url . ':' . $html . ':' . $rc . ']' . "\n";

	if (!$html && $rc != 200) {
		return $rc;
	} else {
		return $html;
	}

}

/**
 * Fetch a URL and parse it as an XML document.
 * Same arguments as getUrl
 */
function url_getter_getDoc($url, $args = array()) {
	$doc = new DOMDocument(); 

	$xml = url_getter_getUrl($url, $args);
	if ($xml && is_string($xml)) {
		$xml = trim($xml); // trim out newlines and other whitespace that might confuse the parser
		$doc->loadXML($xml);
	} 
	return $doc;

}

register_elgg_event_handler('init','system','url_getter_init');
