<?php
$CONFIG = Minds\Core\Di\Di::_()->get('Config');

/*
 * Cassandra configuration
 */
$CONFIG->cassandra = (object) [
    'keyspace'    => '{{cassandra-keyspace}}',
    'servers'     => [ '{{cassandra-server}}' ],
    'cql_servers' => [ '{{cassandra-server}}' ]
];

/**
 * Other Elgg Settings
 */
$CONFIG->installed = true;
$CONFIG->path = '{{path}}';
$CONFIG->plugins_path = '{{path}}plugins/';
$CONFIG->pluginspath = '{{path}}plugins/';
$CONFIG->dataroot = '{{dataroot}}';
$CONFIG->default_site = '{{default-site}}';
$CONFIG->site_id = '{{default-site}}';
$CONFIG->site_name = '{{site-name}}';
$CONFIG->__site_secret__ = '{{site-secret}}';
// $CONFIG->cdn_url = 'http://{{domain}}/';
$CONFIG->site_url = 'http://{{domain}}/';

/**
 * Overrides default system cache path from inside data root to custom location.
 *
 * @global string $CONFIG->system_cache_path
 * @name $CONFIG->system_cache_path
 */
$CONFIG->system_cache_path = '{{cache-path}}';

/**
 * Elasticsearch Settings
 */
//server for elasticsearch
$CONFIG->elasticsearch_server = '{{elasticsearch-server}}';
//namespace
$CONFIG->elasticsearch_prefix = '{{elasticsearch-prefix}}';

/**
 * Memcache setup (optional)
 * This is where you may optionally set up memcache.
 *
 * Requirements:
 * 	1) One or more memcache servers (http://www.danga.com/memcached/)
 *  2) PHP memcache wrapper (http://uk.php.net/manual/en/memcache.setup.php)
 *
 * Note: Multiple server support is only available on server 1.2.1
 * or higher with PECL library > 2.0.0
 */

 /*$CONFIG->memcache = true;

$CONFIG->memcache_servers = array (
    array('server1', 11211),
    array('server2', 11211)
);*/

/**
 * Queue Settings
 */
$CONFIG->queue = [
    'exchange' => '{{ queue-exchange }}'
];

/**
 * Use non-standard headers for broken MTAs.
 *
 * The default header EOL for headers is \r\n.  This causes problems
 * on some broken MTAs.  Setting this to TRUE will cause Elgg to use
 * \n, which will fix some problems sending email on broken MTAs.
 *
 * @global bool $CONFIG->broken_mta
 */
$CONFIG->broken_mta = false;

/**
 * Minimum password length
 *
 * This value is used when validating a user's password during registration.
 *
 * @global int $CONFIG->min_password_length
 */
$CONFIG->min_password_length = 6;

$CONFIG->set('plugins', [
  'Messenger',
  'Groups',
  'blog',
  'archive',
  'thumbs',
]);

$CONFIG->set('sockets-jwt-secret', '{{jwt-secret}}');
$CONFIG->set('sockets-jwt-domain', '{{jwt-domain}}');
$CONFIG->set('sockets-server-uri', '{{socket-server-uri}}');

$CONFIG->set('facebook', [
    'app_id' => '{{facebook-app-id}}',
    'app_secret' => '{{facebook-app-secret}}'
]);

$CONFIG->set('twitter', [
    'app_id' => '{{twitter-app-id}}',
    'app_secret' => '{{twitter-app-id}}'
]);

$CONFIG->set('twilio', [
    'account_sid' => '{{twilio-account-sid}}',
    'auth_token' => '{{twilio-auth-token}}',
    'from' => '{{twilio-from}}'
]);

$CONFIG->set('google', [
    'geolocation' => '{{google-api-key}}',
    'translation' => '{{google-api-key}}',
    'push' => '{{google-api-key}}',
]);

$CONFIG->set('apple', [
    'sandbox' => '{{apple-sandbox-enabled}}',
    'cert' => '{{apple-certificate}}'
]);

$CONFIG->set('boost', [
    'network' => [
        'min' => 100,
        'max' => 5000,
    ],
    'peer' => [
        'min' => 100,
        'max' => 5000000
    ],
]);

$CONFIG->set('encryptionKeys', [
    'email' => [
        'private' => '{{private-key}}',
        'public' => '{{public-key}}'
    ]
]);
