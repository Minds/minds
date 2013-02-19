<?php

$minds_url    = get_plugin_setting('minds_url', 'minds_connect');
$client_id    = get_plugin_setting('client_id', 'minds_connect');
$ssl_callback = get_plugin_setting('ssl_callback', 'minds_connect');

if ($ssl_callback == 'yes') {
    $parts    = parse_url(elgg_get_site_url());
    $callback = 'https://' . $parts['host'] . '/minds_connect/authorized';
} else {
    $callback = elgg_get_site_url() . 'minds_connect/authorized';
}

$callback = urlencode($callback);



if (isset($_COOKIE['MC'])) {
    $url = elgg_get_site_url() . 'minds_connect/login';
} else {
    $url = "{$minds_url}/oauth2/authorize?response_type=code&client_id={$client_id}&redirect_uri={$callback}";
}

?>

<a href="<?php echo $url; ?>" class="elgg-button elgg-button-action">Minds Login</a>
