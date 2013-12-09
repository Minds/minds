<?php
/**
 * Defines database credentials.
 *
 * Most of Elgg's configuration is stored in the database.  This file contains the
 * credentials to connect to the database, as well as a few optional configuration
 * values.
 *
 * The Elgg installation attempts to populate this file with the correct settings
 * and then rename it to settings.php.
 *
 * @todo Turn this into something we handle more automatically.
 * @package Elgg.Core
 * @subpackage Configuration
 */

global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}

/*
 * Cassandra configuration
 *
 * You will use the same database connection for reads and writes.
 * This is the easiest configuration, and will suit 99.99% of setups. However, if you're
 * running a really popular site, you'll probably want to spread out your database connections
 * and implement database replication.  That's beyond the scope of this configuration file
 * to explain, but if you know you need it, skip past this section.
 */
$CONFIG->cassandra = new stdClass;
$CONFIG->cassandra->keyspace = '{{keyspace}}';
$CONFIG->cassandra->servers = array('{{server}}');


/** 
 * Other Elgg Settings
 */
$CONFIG->installed = '{{installed}}';
$CONFIG->path = '{{path}}';
$CONFIG->dataroot = '{{dataroot}}';
$CONFIG->default_site = '{{default_site}}';
$CONFIG->site_id = '{{default_site}}';
//$CONFIG->__site_secret__ = md5(rand() . microtime());
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
$CONFIG->elasticsearch_server = 'http://107.23.117.9:9200/';
//namespace
$CONFIG->elasticsearch_prefix = 'mehmac_';

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
 * Disable the database query cache
 *
 * Elgg stores each query and its results in a query cache.
 * On large sites or long-running scripts, this cache can grow to be
 * large.  To disable query caching, set this to TRUE.
 *
 * @global bool $CONFIG->db_disable_query_cache
 */
$CONFIG->db_disable_query_cache = FALSE;

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
