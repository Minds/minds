<?php

global $CONFIG;

/*
 * Cassandra configuration
 *
 */
$CONFIG->cassandra = new stdClass;
$CONFIG->cassandra->keyspace = 'meh4';
$CONFIG->cassandra->servers = array('localhost');

/** 
 * Other Elgg Settings
 */
$CONFIG->installed = '1390304771';
$CONFIG->path = '/wwwroot/minds/';
$CONFIG->dataroot = '/mindsdata/';
$CONFIG->default_site = '1';
$CONFIG->site_id = '1';
$CONFIG->__site_secret__ = '123mvkvl';
/**
 * Overrides default system cache path from inside data root to custom location.
 *
 * @global string $CONFIG->system_cache_path
 * @name $CONFIG->system_cache_path
 */
$CONFIG->system_cache_path = '/tmp/elgg_system_cache/';

/**
 * Elasticsearch Settings
 */
//server for elasticsearch
$CONFIG->elasticsearch_server = 'http://108.82.235.132:9200/';
//namespace
$CONFIG->elasticsearch_prefix = 'io_';

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
//$CONFIG->memcache = true;
//
//$CONFIG->memcache_servers = array (
//	array('server1', 11211),
//	array('server2', 11211)
//);


/**
 * Use non-standard headers for broken MTAs.
 *
 * The default header EOL for headers is \r\n.  This causes problems
 * on some broken MTAs.  Setting this to TRUE will cause Elgg to use
 * \n, which will fix some problems sending email on broken MTAs.
 *
 * @global bool $CONFIG->broken_mta
 */
$CONFIG->broken_mta = FALSE;


/**
 * Minimum password length
 *
 * This value is used when validating a user's password during registration.
 *
 * @global int $CONFIG->min_password_length
 */
$CONFIG->min_password_length = 6;

/**
 * Where multisite admin endpoint is installed
 */
$CONFIG->multisite_endpoint = "http://minds-multi.minds.io/minds/"; // Where the Web services endpoint is
$CONFIG->multisite_server_ip = "54.236.202.136"; // IP address to prompt people to set DNS to
$CONFIG->minds_multisite_root_domain = '.minds.com'; // Suffix for new nodes